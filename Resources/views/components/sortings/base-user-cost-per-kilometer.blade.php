<x-cars::sortings.master  :vehicle="$vehicle" headLine="{{ decimal($vehicle->sortingValue, 2) }} €">

    <x-slot name="infoText">
        <p>Vergleichspreis pro km<br>inkl. aller Kosten außer Tanken</p>
        <p>bei {{ decimal($parameters['km'], 0) }} km monatlich</p>

        <p>
            Tatsächliche monatliche Kosten<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseUserCostPerMonth, 2) }} €
            @if($vehicle->calc->totalUserCostPerMonth)
                <br>inkl. Tanken: {{ decimal($vehicle->calc->totalUserCostPerMonth, 2) }} €
            @endif
        </p>

        @if($vehicle->calc->totalUserCostPerKilometer)
            <p>
                Vergleichspreis pro km<br>
                inkl. Tanken: {{ decimal($vehicle->calc->totalUserCostPerKilometer, 2) }} €
            </p>
        @endif
    </x-slot>

</x-cars::sortings.master>
