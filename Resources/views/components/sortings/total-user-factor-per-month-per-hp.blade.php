<x-cars::sortings.master  :vehicle="$vehicle" headLine="{{ decimal(10_000 * $vehicle->sortingValue, 3) }}">

    <x-slot name="infoText">
        <p>Abo-Faktor pro Monat pro 100 PS<br>inkl. aller Kosten inkl. Tanken</p>
        <p>bei {{ decimal($parameters['km'], 0) }} km monatlich</p>

        <p>
            Grundlage:<br>
            Leistung: {{ decimal($vehicle->power_hp, 0) }} PS<br>
            Monatliche Kosten: {{ decimal($vehicle->calc->totalUserCostPerMonth, 2) }} €<br>
            Listenpreis laut Anbieter: {{ decimal($vehicle->listingPrice, 2) }} €
        </p>

        <p>
            Tatsächliche monatliche Kosten<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseUserCostPerMonth, 2) }} €
            @if($vehicle->calc->totalUserCostPerMonth)
                <br>inkl. Tanken: {{ decimal($vehicle->calc->totalUserCostPerMonth, 2) }} €
            @endif
        </p>

        <p>
            Vergleichspreis pro km<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseUserCostPerKilometer, 2) }} €
            @if($vehicle->calc->totalUserCostPerKilometer)
                <br>inkl. Tanken: {{ decimal($vehicle->calc->totalUserCostPerKilometer, 2) }} €
            @endif
        </p>
    </x-slot>

</x-cars::sortings.master>
