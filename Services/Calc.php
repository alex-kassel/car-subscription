<?php

declare(strict_types=1);

namespace CarSubscription\Services;

use Illuminate\Support\Arr;

class Calc
{
    protected object $calc;

    public function __construct(
        protected ?int $kilometers,
        protected ?float $price,
        protected ?float $additionalKilometerCost,
        protected ?int $maxAdditionalKilometers,
        protected ?float $consumption,
        protected ?float $fuelPrice,
        protected ?int $listingPrice,
        protected ?int $powerHp,
        protected ?int $userKilometers,
    ) {
        #dd($this);
        $this->calc = (object) [];

        $this->calculateDefaultCosts();
        $this->calculateUserCosts();

        $this->calculateAdditionalUserCosts();
    }

    public static function create($item, $plan, $userKilometers, &$c = 0): object {
        $item = (array) $item;
        $plan = (array) $plan;
        #dd($item, $plan);
        #logger(sprintf('%s - %s - %s', $c++, exec_time(), exec_time()/$c));

        $obj = new static(
            kilometers: Arr::get($plan, 'kilometers'),
            price: Arr::get($plan, 'price'),
            additionalKilometerCost: Arr::get($item, 'additional_kilometer_cost'),
            maxAdditionalKilometers: Arr::get($item, 'max_additional_kilometers'),
            consumption: Arr::get($item, 'consumption'),
            fuelPrice: Arr::get($item, 'enums.fuel') == 'ELECTRIC' ? 0.5 : 1.6,
            listingPrice: Arr::get($item, 'listing_price'),
            powerHp: Arr::get($item, 'power_hp'),
            userKilometers: $userKilometers,
        );
        return $obj->calc;
    }

    public function all(): object {
        return $this->calc;
    }

    public function calculateDefaultCosts(): void {
        $this->calc->baseDefaultCostPerMonth = $this->price;

        $this->calc->fuelDefaultCostPerMonth = $this->fuelCost(
            fuelPrice: $this->fuelPrice,
            consumption: $this->consumption,
            kilometers: $this->kilometers
        );

        $this->calc->totalDefaultCostPerMonth = $this->totalCost(
            baseCost: $this->calc->baseDefaultCostPerMonth,
            fuelCost: $this->calc->fuelDefaultCostPerMonth,
        );

        $this->calc->baseDefaultCostPerKilometer = $this->costPerKilometer(
            price: $this->price,
            kilometers: $this->kilometers,
        );

        $this->calc->totalDefaultCostPerKilometer = $this->costPerKilometer(
            price: $this->calc->totalDefaultCostPerMonth,
            kilometers: $this->kilometers,
        );

        $this->calc->baseDefaultCostPerKilometer = $this->costPerKilometer(
            price: $this->price,
            kilometers: $this->kilometers,
        );

        $this->calc->totalDefaultCostPerKilometer = $this->costPerKilometer(
            price: ($this->calc->totalDefaultCostPerMonth ?? 0),
            kilometers: $this->kilometers,
        );

        $this->calc->baseDefaultFactorPerMonth = $this->factor(
            cost: $this->calc->baseDefaultCostPerMonth,
            value: $this->listingPrice,
        );

        $this->calc->totalDefaultFactorPerMonth = $this->factor(
            cost: $this->calc->totalDefaultCostPerMonth,
            value: $this->listingPrice,
        );

        $this->calc->baseDefaultFactorPerKilometer = $this->factor(
            cost: $this->calc->baseDefaultCostPerKilometer,
            value: $this->listingPrice,
        );

        $this->calc->totalDefaultFactorPerKilometer = $this->factor(
            cost: $this->calc->totalDefaultCostPerKilometer,
            value: $this->listingPrice,
        );
    }

    public function calculateUserCosts(): void {
        $this->calc->additionalKilometers = $this->additionalKilometers(
            kilometers: $this->kilometers,
            userKilometers: $this->userKilometers,
        );

        $this->calc->additionalKilometersCost = $this->additionalKilometersCost(
            additionalKilometers: $this->calc->additionalKilometers,
            additionalKilometerCost: $this->additionalKilometerCost,
        );

        $this->calc->baseUserCostPerMonth = $this->baseUserCostPerMonth(
            price: $this->price,
            additionalKilometersCost: $this->calc->additionalKilometersCost,
        );

        $this->calc->fuelUserCostPerMonth = $this->fuelCost(
            fuelPrice: $this->fuelPrice,
            consumption: $this->consumption,
            kilometers: $this->userKilometers
        );

        $this->calc->totalUserCostPerMonth = $this->totalCost(
            baseCost: $this->calc->baseUserCostPerMonth,
            fuelCost: $this->calc->fuelUserCostPerMonth,
        );

        $this->calc->baseUserCostPerKilometer = $this->costPerKilometer(
            price: $this->calc->baseUserCostPerMonth,
            kilometers: $this->userKilometers,
        );

        $this->calc->totalUserCostPerKilometer = $this->costPerKilometer(
            price: $this->calc->totalUserCostPerMonth,
            kilometers: $this->userKilometers,
        );

        $this->calc->baseUserFactorPerMonth = $this->factor(
            cost: $this->calc->baseUserCostPerMonth,
            value: $this->listingPrice,
        );

        $this->calc->totalUserFactorPerMonth = $this->factor(
            cost: $this->calc->totalUserCostPerMonth,
            value: $this->listingPrice,
        );

        $this->calc->baseUserFactorPerKilometer = $this->calc->baseDefaultFactorPerKilometer;
        $this->calc->totalUserFactorPerKilometer = $this->calc->totalDefaultFactorPerKilometer;
    }

    public function calculateAdditionalUserCosts(): void {
        $this->calc->baseDefaultCostPerMonthPerHp = $this->costPerPowerHp(
            cost: $this->calc->baseDefaultCostPerMonth,
            powerHp: $this->powerHp,
        );

        $this->calc->totalDefaultCostPerMonthPerHp = $this->costPerPowerHp(
            cost: $this->calc->totalDefaultCostPerMonth,
            powerHp: $this->powerHp,
        );

        $this->calc->baseDefaultCostPerKilometerPerHp = $this->costPerPowerHp(
            cost: $this->calc->baseDefaultCostPerKilometer,
            powerHp: $this->powerHp,
        );

        $this->calc->totalDefaultCostPerKilometerPerHp = $this->costPerPowerHp(
            cost: $this->calc->totalDefaultCostPerKilometer,
            powerHp: $this->powerHp,
        );

        $this->calc->baseDefaultFactorPerKilometerPerHp = $this->factor(
            cost: $this->calc->baseDefaultCostPerKilometerPerHp,
            value: $this->listingPrice,
        );

        $this->calc->totalDefaultFactorPerKilometerPerHp = $this->factor(
            cost: $this->calc->totalDefaultCostPerKilometerPerHp,
            value: $this->listingPrice,
        );

        $this->calc->baseDefaultFactorPerMonthPerHp = $this->factor(
            cost: $this->calc->baseDefaultCostPerMonthPerHp,
            value: $this->listingPrice,
        );

        $this->calc->totalDefaultFactorMonthPerHp = $this->factor(
            cost: $this->calc->totalDefaultCostPerMonthPerHp,
            value: $this->listingPrice,
        );

        $this->calc->baseUserCostPerMonthPerHp = $this->costPerPowerHp(
            cost: $this->calc->baseUserCostPerMonth,
            powerHp: $this->powerHp,
        );

        $this->calc->baseUserCostPerKilometerPerHp = $this->costPerPowerHp(
            cost: $this->calc->baseUserCostPerKilometer,
            powerHp: $this->powerHp,
        );

        $this->calc->totalUserCostPerKilometerPerHp = $this->costPerPowerHp(
            cost: $this->calc->totalUserCostPerKilometer,
            powerHp: $this->powerHp,
        );

        $this->calc->totalUserCostPerMonthPerHp = $this->costPerPowerHp(
            cost: $this->calc->totalUserCostPerMonth,
            powerHp: $this->powerHp,
        );
    }

    public function costPerPowerHp(?float $cost, ?int $powerHp): ?float {
        return !(empty($cost) || empty($powerHp))
            ? round($cost / $powerHp, 10)
            : null;
    }
    public function costPerKilometer(?float $price, ?int $kilometers,): ?float {
        return !(empty($price) || empty($kilometers))
            ? round($price / $kilometers, 10)
            : null;
    }

    public function fuelCost(?float $fuelPrice, ?float $consumption, ?int $kilometers): ?float {
        return !(empty($fuelPrice) || empty($consumption) || empty($kilometers))
            ? round($kilometers * $consumption * $fuelPrice / 100, 10)
            : null;
    }

    public function totalCost(?float $baseCost, ?float $fuelCost): ?float {
        return !(empty($baseCost) || empty($fuelCost))
            ? $baseCost + $fuelCost
            : null;
    }

    public function factor(?float $cost, ?float $value): ?float {
        return !(empty($cost) || empty($value))
            ? round($cost / $value, 10)
            : null;
    }

    public function additionalKilometers(?int $kilometers, ?int $userKilometers): ?int {
        return !(empty($kilometers) || empty($userKilometers))
            ? max(0, $userKilometers - $kilometers)
            : null;
    }

    public function additionalKilometersCost(?int $additionalKilometers, ?float $additionalKilometerCost): ?float
    {
        return !(is_null($additionalKilometers) || empty($additionalKilometerCost))
            ? $additionalKilometerCost * $additionalKilometers
            : null;
    }

    public function baseUserCostPerMonth(?float $price, ?float $additionalKilometersCost): ?float {
        return !(empty($price) || is_null($additionalKilometersCost))
            ? $price + $additionalKilometersCost
            : null;
    }
}
