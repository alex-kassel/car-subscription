<x-cars::sortings.master  :vehicle="$vehicle" headLine="{{ decimal(1000 * $vehicle->sortingValue, 2) }} €">

    <x-slot name="infoText">
        <p>Vergleichspreis pro 1.000 km pro PS<br>inkl. aller Kosten inkl. Tanken</p>
        <p>bei {{ decimal($vehicle->plan->kilometers, 0) }} km monatlich</p>

        <p>
            Tatsächliche monatliche Kosten<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseDefaultCostPerMonth, 2) }} €<br>
            inkl. Tanken: {{ decimal($vehicle->calc->totalDefaultCostPerMonth, 2) }} €
        </p>

        <p>
            Kalkulatorische Kosten pro km<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseDefaultCostPerKilometer, 2) }} €<br>
            inkl. Tanken: {{ decimal($vehicle->calc->totalDefaultCostPerKilometer, 2) }} €
        </p>

        <p>
            Vergleichspreis pro 1.000 km pro PS<br>
            ohne Tanken: {{ decimal(1000 * $vehicle->calc->baseDefaultCostPerKilometerPerHp, 2) }} €
        </p>
    </x-slot>

</x-cars::sortings.master>
