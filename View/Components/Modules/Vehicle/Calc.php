<?php

declare(strict_types=1);

namespace CarSubscription\View\Components\Modules\Vehicle;

use Illuminate\View\Component;
use Illuminate\View\View;
use Shared\Models\BaseModel;

class Calc extends Component
{
    public object $sorting;

    public function __construct(
        public BaseModel $vehicle,
        public array $parameters,
    ) {
        $this->sorting = $this->getSorting();
    }

    protected function getSorting(): object
    {
        $sortingKey = explode('-', $this->parameters['sort'], 3)[2];
        $sorting = (object) $this->getSortingOptions()[$sortingKey];

        $sorting->headLine = str_contains($this->parameters['sort'], 'cost')
            ? decimal($sorting->multiplier * $this->vehicle->sortingValue, 2) . ' €'
            : decimal($sorting->multiplier * $this->vehicle->sortingValue, 3)
        ;

        return $sorting;
    }
    protected function getSortingOptions()
    {
        return [
            'cost-per-kilometer' => [
                'multiplier' => 1,
                'option' => 'Kosten pro km',
                'text' => 'Vergleichspreis pro Kilometer'
            ],
            'cost-per-kilometer-per-hp' => [
                'multiplier' => 1000,
                'option' => 'Kosten pro 1.000 km pro PS',
                'text' => 'Vergleichspreis pro 1.000 km pro PS'
            ],
            'cost-per-month' => [
                'multiplier' => 1,
                'option' => 'Kosten pro Monat',
                'text' => 'Gesamtkosten pro Monat'
            ],
            'cost-per-month-per-hp' => [
                'multiplier' => 1,
                'option' => 'Kosten pro Monat pro PS',
                'text' => 'Gesamtkosten pro Monat pro PS'
            ],
            'factor-per-kilometer' => [
                'multiplier' => 100 * 1000,
                'option' => 'Abo-Faktor pro 1.000 km',
                'text' => 'Abo-Faktor pro 1.000 km',
            ],
            'factor-per-kilometer-per-hp' => [
                'multiplier' => 100 * 1000 * 100,
                'option' => 'Abo-Faktor pro 1.000 km pro 100 PS',
                'text' => 'Abo-Faktor pro 1.000 km pro 100 PS',
            ],
            'factor-per-month' => [
                'multiplier' => 100,
                'option' => 'Abo-Faktor pro Monat',
                'text' => 'Abo-Faktor pro Monat',
            ],
            'factor-per-month-per-hp' => [
                'multiplier' => 100 * 100,
                'option' => 'Abo-Faktor pro Monat pro 100 PS',
                'text' => 'Abo-Faktor pro Monat pro 100 PS',
            ],
        ];
    }

    public function render(): View
    {
        #return view('cars::components.sortings.master-flomaster');
        return view('cars::components.sortings.' . $this->parameters['sort']);
    }
}
