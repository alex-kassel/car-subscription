<x-cars::sortings.master  :vehicle="$vehicle" headLine="{{ decimal(100 * $vehicle->sortingValue, 3) }}">

    <x-slot name="infoText">
        <p>Abo-Faktor pro Monat<br>inkl. aller Kosten außer Tanken</p>
        <p>bei {{ decimal($vehicle->plan->kilometers, 0) }} km monatlich</p>

        <p>
            Grundlage:<br>
            Monatsrate: {{ decimal($vehicle->calc->baseDefaultCostPerMonth, 2) }} €<br>
            Listenpreis laut Anbieter: {{ decimal($vehicle->listingPrice, 2) }} €
        </p>

        <p>
            Tatsächliche monatliche Kosten<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseDefaultCostPerMonth, 2) }} €
            @if($vehicle->calc->totalDefaultCostPerMonth)
                <br>inkl. Tanken: {{ decimal($vehicle->calc->totalDefaultCostPerMonth, 2) }} €
            @endif
        </p>

        <p>
            Vergleichspreis pro km<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseDefaultCostPerKilometer, 2) }} €
            @if($vehicle->calc->totalDefaultCostPerKilometer)
                <br>inkl. Tanken: {{ decimal($vehicle->calc->totalDefaultCostPerKilometer, 2) }} €
            @endif
        </p>
    </x-slot>

</x-cars::sortings.master>
