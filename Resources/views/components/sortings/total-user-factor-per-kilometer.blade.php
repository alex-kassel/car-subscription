<x-cars::sortings.master  :vehicle="$vehicle" headLine="{{ decimal(100_000 * $vehicle->sortingValue, 3) }}">

    <x-slot name="infoText">
        <p>Abo-Faktor pro 1.000 km<br>inkl. aller Kosten inkl. Tanken</p>
        <p>bei {{ decimal($parameters['km'], 0) }} km monatlich</p>

        <p>
            Grundlage:<br>
            Kosten pro 1.000 km: {{ decimal(1000 * $vehicle->calc->totalUserCostPerKilometer, 2) }} €<br>
            Listenpreis laut Anbieter: {{ decimal($vehicle->listingPrice, 2) }} €
        </p>

        <p>
            Tatsächliche monatliche Kosten<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseUserCostPerMonth, 2) }} €<br>
            inkl. Tanken: {{ decimal($vehicle->calc->totalUserCostPerMonth, 2) }} €
        </p>

        <p>
            Vergleichspreis pro km<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseUserCostPerKilometer, 2) }} €<br>
            inkl. Tanken: {{ decimal($vehicle->calc->totalUserCostPerKilometer, 2) }} €
        </p>
    </x-slot>

</x-cars::sortings.master>

