<x-cars::sortings.master  :vehicle="$vehicle" headLine="{{ decimal($vehicle->sortingValue, 0) }} €">

    <x-slot name="infoText">
        <p>Monatliche Gesamtkosten<br>inkl. Tanken</p>
        <p>bei {{ decimal($parameters['km'], 0) }} km monatlich</p>

        <p>
            Kalkulatorische Kosten pro km<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseUserCostPerKilometer, 2) }} €<br>
            inkl. Tanken {{ decimal($vehicle->calc->totalUserCostPerKilometer, 2) }} €
        </p>

        <p>
            Monatliche Kosten<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseUserCostPerMonth, 2) }} €
        </p>
    </x-slot>

</x-cars::sortings.master>
