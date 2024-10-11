<x-cars::sortings.master  :vehicle="$vehicle" headLine="{{ decimal(1000 * $vehicle->sortingValue, 2) }} €">

    <x-slot name="infoText">
        <p>Vergleichspreis pro 1.000 km pro PS<br>inkl. aller Kosten außer Tanken</p>
        <p>bei {{ decimal($parameters['km'], 0) }} km monatlich</p>

        <p>
            Tatsächliche monatliche Kosten<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseUserCostPerMonth, 2) }} €
            @if($vehicle->calc->totalUserCostPerMonth)
                <br>inkl. Tanken: {{ decimal($vehicle->calc->totalUserCostPerMonth, 2) }} €
            @endif
        </p>

        <p>
            Kalkulatorische Kosten pro km<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseUserCostPerKilometer, 2) }} €
            @if($vehicle->calc->totalUserCostPerKilometer)
                <br>inkl. Tanken: {{ decimal($vehicle->calc->totalUserCostPerKilometer, 2) }} €
            @endif
        </p>

        @if($vehicle->calc->totalUserCostPerKilometerPerHp)
        <p>
            Vergleichspreis pro 1.000 km pro PS<br>
            inkl. Tanken: {{ decimal(1000 * $vehicle->calc->totalUserCostPerKilometerPerHp, 2) }} €
        </p>
        @endif
    </x-slot>

</x-cars::sortings.master>
