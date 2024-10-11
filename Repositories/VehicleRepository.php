<?php

declare(strict_types=1);

namespace CarSubscription\Repositories;

use CarSubscription\Enums\Condition;
use CarSubscription\Enums\DrivetrainType;
use CarSubscription\Enums\FuelType;
use CarSubscription\Enums\TransmissionType;
use CarSubscription\Enums\VehicleType;
use CarSubscription\Models\PaymentPlan;
use CarSubscription\Models\Vehicle;
use CarSubscription\Services\Calc;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VehicleRepository
{
    public function getVehicles(array $parameters, int $limit): Collection
    {
        $searchResult = $this->searchResultFromCache($parameters, $limit);
        #dd($searchResult->pluck('sorting_value'));

        $vehicleIds = $searchResult->pluck('vehicle_id');
        $vehicles = Vehicle::whereIn('id', $vehicleIds)->get()->keyBy('id');

        $planIds = $searchResult->pluck('plan_id');
        $plans = PaymentPlan::whereIn('id', $planIds)->get()->keyBy('id');

        $fuelCosts = $this->getFuelCosts();

        return $searchResult->map(function ($searchItem) use ($vehicles, $plans, $parameters, $fuelCosts) {
            if (!$vehicle = $vehicles->get($searchItem->vehicle_id)) {
                return null;
            }

            if (!$vehicle->plan = $plans->get($searchItem->plan_id)) {
                return null;
            }

            $vehicle->sortingValue = $searchItem->sorting_value;
            $vehicle->fuelCost = $fuelCosts->get($vehicle->fuel);

            $vehicle->calc = (new Calc(
                kilometers: $vehicle->plan->kilometers,
                price: $vehicle->plan->price,
                additionalKilometerCost: $vehicle->plan->additionalKilometerCost ?? $vehicle->additionalKilometerCost,
                maxAdditionalKilometers: $vehicle->maxAdditionalKilometers,
                consumption: $vehicle->consumption,
                fuelPrice: $vehicle->fuelCost,
                listingPrice: $vehicle->listingPrice,
                powerHp: $vehicle->powerHp,
                userKilometers: $parameters['km'],
            ))->all();

            $vehicle->condition = Condition::getValue($vehicle->condition);
            $vehicle->drivetrain = DrivetrainType::getValue($vehicle->drivetrain);
            $vehicle->fuel = FuelType::getValue($vehicle->fuel);
            $vehicle->transmission = TransmissionType::getValue($vehicle->transmission);
            $vehicle->category = VehicleType::getValue($vehicle->category);

            return $vehicle;
        })->filter();
    }

    protected function getFuelCosts(): Collection
    {
        return DB::connection('cars')->table('cs_fuel_prices')->pluck('price', 'fuel');
    }

    protected function searchResultFromCache(array $parameters, int $limit): Collection
    {
        if (str_contains($parameters['sort'],'default')) {
            unset($parameters['km']);
        }

        $searchResult = cache()->rememberForever(
            key: 'vehicles?' .  http_build_query($parameters),
            callback: fn () => $this->searchResultFromDB($parameters, $limit)
                ->each(function ($result) {
                    $result->sorting_value = floatval($result->sorting_value);
                })->toJson()
            ,
        );

        return collect(json_decode($searchResult));
    }
    protected function searchResultFromDB(array $parameters, int $limit): Collection
    {
        $connection = DB::connection('cars');

        $query = $connection->table('cs_vehicles as v')
            ->join('cs_plans as p', 'v.id', '=', 'p.vehicle_id')
            ->when(str_contains($parameters['sort'], 'total'), fn ($query) => $query->join('cs_fuel_prices as f', 'v.fuel', '=', 'f.fuel'))
            ->selectRaw('p.vehicle_id, p.id plan_id, (' . $this->getSortingQuery($parameters['sort'], $parameters['km'] ?? 0) . ') as sorting_value')
            ->whereNull('p.deleted_at')
            ->tap(fn ($query) => $this->setFilters($query, $parameters))
        ;

        $query = $connection->table($query, 't')
            ->selectRaw("vehicle_id, substring_index(group_concat(plan_id order by sorting_value), ',', 1) plan_id, min(sorting_value) sorting_value")
            ->whereNotNull('sorting_value')
            ->groupBy('vehicle_id')
            ->orderBy('sorting_value')
            ->take($limit)
        ;

        #dump($query->toRawSql());
        return $query->get();
    }

    protected function getSortingQuery(string $sortingKey, int $userKilometers): string
    {
        $additionalKilometers = "greatest(0, $userKilometers - p.kilometers)";
        $maxAdditionalKilometers = "floor(ifnull(v.max_additional_kilometers, 0) / p.kilometers)";
        $additionalKilometerCost = "ifnull(p.additional_kilometer_cost, v.additional_kilometer_cost)";

        $additionalKilometersSubCondition = "case when $maxAdditionalKilometers between 1 and $additionalKilometers then null else $additionalKilometers end";
        $additionalKilometersWithCondition = "(case when $additionalKilometers > 0 then ($additionalKilometersSubCondition) else 0 end)";

        return [
            'base-default-cost-per-month' => "p.price",
            'base-default-cost-per-kilometer' => "p.price / p.kilometers",
            'base-default-factor-per-month' => "p.price / v.listing_price",
            'base-default-factor-per-kilometer' => "p.price / p.kilometers / v.listing_price",

            'base-default-cost-per-month-per-hp' => "p.price / v.power_hp",
            'base-default-cost-per-kilometer-per-hp' => "p.price / p.kilometers / v.power_hp",
            'base-default-factor-per-month-per-hp' => "p.price / v.listing_price / v.power_hp",
            'base-default-factor-per-kilometer-per-hp' => "p.price / p.kilometers / v.power_hp / v.listing_price",

            'base-user-cost-per-month' => $baseUserCostPerMonth = "(p.price + $additionalKilometerCost * $additionalKilometersWithCondition)",
            'base-user-cost-per-kilometer' => "$baseUserCostPerMonth / $userKilometers",
            'base-user-factor-per-month' => "$baseUserCostPerMonth / v.listing_price",
            'base-user-factor-per-kilometer' => "$baseUserCostPerMonth / $userKilometers / v.listing_price",

            'base-user-cost-per-month-per-hp' => "$baseUserCostPerMonth / v.power_hp",
            'base-user-cost-per-kilometer-per-hp' => "$baseUserCostPerMonth / $userKilometers / v.power_hp",
            'base-user-factor-per-month-per-hp' => "$baseUserCostPerMonth / v.power_hp / v.listing_price",
            'base-user-factor-per-kilometer-per-hp' => "$baseUserCostPerMonth / $userKilometers / v.power_hp / v.listing_price",

            'total-default-cost-per-month' => $totalDefaultCostPerMonth = "(p.price + v.consumption * f.price * p.kilometers / 100)",
            'total-default-cost-per-kilometer' => "$totalDefaultCostPerMonth / p.kilometers",
            'total-default-factor-per-month' => "$totalDefaultCostPerMonth / v.listing_price",
            'total-default-factor-per-kilometer' => "$totalDefaultCostPerMonth / p.kilometers / v.listing_price",

            'total-default-cost-per-month-per-hp' => "$totalDefaultCostPerMonth / v.power_hp",
            'total-default-cost-per-kilometer-per-hp' => "$totalDefaultCostPerMonth / p.kilometers / v.power_hp",
            'total-default-factor-per-month-per-hp' => "$totalDefaultCostPerMonth / v.listing_price / v.power_hp",
            'total-default-factor-per-kilometer-per-hp' => "$totalDefaultCostPerMonth / p.kilometers / v.power_hp / v.listing_price",

            'total-user-cost-per-month' => $totalUserCostPerMonth = "($baseUserCostPerMonth + v.consumption * f.price * $userKilometers / 100)",
            'total-user-cost-per-kilometer' => "$totalUserCostPerMonth / $userKilometers",
            'total-user-factor-per-month' => "$totalUserCostPerMonth / v.listing_price",
            'total-user-factor-per-kilometer' => "$totalUserCostPerMonth / $userKilometers / v.listing_price",

            'total-user-cost-per-month-per-hp' => "$totalUserCostPerMonth / v.power_hp",
            'total-user-cost-per-kilometer-per-hp' => "$totalUserCostPerMonth / $userKilometers / v.power_hp",
            'total-user-factor-per-month-per-hp' => "$totalUserCostPerMonth / v.power_hp / v.listing_price",
            'total-user-factor-per-kilometer-per-hp' => "$totalUserCostPerMonth / $userKilometers / v.power_hp / v.listing_price",
        ][$sortingKey];

        /*
            update cs_sortings set multiplier=100 WHERE name LIKE '%factor%';
            update cs_sortings set multiplier=multiplier*1000 WHERE name LIKE '%factor%meter%';
            update cs_sortings set multiplier=multiplier*100 WHERE name LIKE '%factor%hp%';
            select * from  cs_sortings WHERE name LIKE '%factor%';
         */
    }

    protected function setFilters(Builder $query, array $parameters): Builder
    {
        return $query
            ->tap(fn ($query) => $query->whereNotIn('v.category', ['OTHER']))
            ->tap(fn ($query) => $this->setFilterMatch($query, $parameters['match'] ?? null))
            ->tap(fn ($query) => $this->whereBetween($query, 'v.power_hp', $parameters['hp'] ?? null))
            ->tap(fn ($query) => $this->whereBetween($query, 'p.kilometers', $parameters['kms'] ?? null))
            ->tap(fn ($query) => $this->whereBetween($query, 'p.duration', $parameters['months'] ?? null))
            ->tap(fn ($query) => $this->whereIn($query, 'v.drivetrain', $parameters['drivetrain'] ?? null))
            ->tap(fn ($query) => $this->whereIn($query, 'v.category', $parameters['category'] ?? null))
            ;
    }

    protected function setFilterMatch(Builder $query, ?string $value): Builder
    {
        return $value
            ? $query->where('v.title', 'like', str_replace([' '], '%', "%$value%"))
            : $query
        ;
    }

    protected function whereIn(Builder $query, string $key, ?string $value): Builder
    {
        return $value
            ? $query->whereIn($key, explode(',', $value))
            : $query
            ;
    }

    protected function whereBetween(Builder $query, string $key, ?string $value): Builder
    {
        return preg_match('/^(\d+)\.\.(\d+)$/', $value ?? '', $matches)
            ? $query->whereBetween($key, [$matches[1], $matches[2]])
            : $query
            ;
    }
}
