<x-cars::sortings.master  :vehicle="$vehicle" headLine="{{ decimal($vehicle->sortingValue, 2) }} €">

    <x-slot name="infoText">
        <p>Vergleichspreis pro Monat pro PS<br>inkl. aller Kosten außer Tanken</p>
        <p>bei {{ decimal($parameters['km'], 0) }} km monatlich</p>

        <p>
            Tatsächliche monatliche Kosten<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseUserCostPerMonth, 2) }} €
            @if($vehicle->calc->totalUserCostPerMonth > 0)
                <br>inkl. Tanken: {{ decimal($vehicle->calc->totalUserCostPerMonth, 2) }} €
            @endif
        </p>

        <p>
            Kalkulatorische Kosten pro km<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseUserCostPerKilometer, 2) }} €
            @if($vehicle->calc->totalUserCostPerKilometer > 0)
                <br>inkl. Tanken: {{ decimal($vehicle->calc->totalUserCostPerKilometer, 2) }} €
            @endif
        </p>

        @if($vehicle->calc->totalUserCostPerMonthPerHp > 0)
            <p>
                Vergleichspreis pro Monat pro PS<br>
                inkl. Tanken: {{ decimal($vehicle->calc->totalUserCostPerMonthPerHp, 2) }} €
            </p>
        @endif
    </x-slot>

</x-cars::sortings.master>
