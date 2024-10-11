<?php

declare(strict_types=1);

namespace CarSubscription;

class DomainService
{

    protected array $parameters;

    public function __construct()
    {

    }

    public function parameters(): array
    {
        return $this->parameters ??= array_filter([
            'sort' => request('sort') ?? 'base-default-cost-per-month',
            'km' => intval(request('km')) ?: 1750,

            'hp' => request('hp'),
            'kms' => request('kms'),
            'match' => request('match'),
            'months' => request('months'),
            'drivetrain' => request('drivetrain'),
            'category' => request('category'),
        ]);
    }

    public function options(string $options): ?array
    {
        return match ($options) {
            'user_kilometers' => $this->getUserKilometerOptions(),
            'sorting' => $this->getSortingOptions(),
            default => null,
        };
    }

    protected function getUserKilometerOptions(): array
    {
        return [
            ...range(500, 2_500, 250),
            ...range(3_000, 6_000, 500),
            ...range(7_000, 10_000, 1_000),
        ];
    }

    public function getSortingOptions(): array
    {
        return require __DIR__.'/Sources/sorting-options.php';
    }
}
