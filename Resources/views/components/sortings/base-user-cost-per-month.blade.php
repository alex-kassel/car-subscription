<x-cars::sortings.master  :vehicle="$vehicle" headLine="{{ decimal($vehicle->sortingValue, 0) }} €">

    <x-slot name="infoText">
        <p>Monatliche Gesamtkosten<br>ohne Tanken</p>
        <p>bei {{ decimal($parameters['km'], 0) }} km monatlich</p>

        <p>
            Kalkulatorische Kosten pro km<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseUserCostPerKilometer, 2) }} €
            @if($vehicle->calc->totalUserCostPerKilometer)
                <br>inkl. Tanken {{ decimal($vehicle->calc->totalUserCostPerKilometer, 2) }} €
            @endif
        </p>

        @if($vehicle->calc->totalUserCostPerMonth)
            <p>
                Monatliche Kosten<br>
                inkl. Tanken: {{ decimal($vehicle->calc->totalUserCostPerMonth, 2) }} €
            </p>
        @endif
    </x-slot>

</x-cars::sortings.master>
