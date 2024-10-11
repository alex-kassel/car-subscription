<x-cars::sortings.master  :vehicle="$vehicle" headLine="{{ decimal(1000 * $vehicle->sortingValue, 2) }} €">

    <x-slot name="infoText">
        <p>Vergleichspreis pro 1.000 km pro PS<br>inkl. aller Kosten außer Tanken</p>
        <p>bei {{ decimal($vehicle->plan->kilometers, 0) }} km monatlich</p>

        <p>
            Tatsächliche monatliche Kosten<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseDefaultCostPerMonth, 2) }} €
            @if($vehicle->calc->totalDefaultCostPerMonth > 0)
                <br>inkl. Tanken: {{ decimal($vehicle->calc->totalDefaultCostPerMonth, 2) }} €
            @endif
        </p>

        <p>
            Kalkulatorische Kosten pro km<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseDefaultCostPerKilometer, 2) }} €
            @if($vehicle->calc->totalDefaultCostPerKilometer > 0)
                <br>inkl. Tanken: {{ decimal($vehicle->calc->totalDefaultCostPerKilometer, 2) }} €
            @endif
        </p>

        @if($vehicle->calc->totalDefaultCostPerKilometerPerHp > 0)
            <p>
                Vergleichspreis pro 1.000 km pro PS<br>
                inkl. Tanken: {{ decimal(1000 * $vehicle->calc->totalDefaultCostPerKilometerPerHp, 2) }} €
            </p>
        @endif
    </x-slot>

</x-cars::sortings.master>
