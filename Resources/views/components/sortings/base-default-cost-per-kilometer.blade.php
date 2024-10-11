<x-cars::sortings.master  :vehicle="$vehicle" headLine="{{ decimal($vehicle->sortingValue, 2) }} €">

    <x-slot name="infoText">
        <p>Vergleichspreis pro km<br>inkl. aller Kosten außer Tanken</p>
        <p>bei {{ decimal($vehicle->plan->kilometers, 0) }} km monatlich</p>

        <p>
            Tatsächliche monatliche Kosten<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseDefaultCostPerMonth, 2) }} €
            @if($vehicle->calc->totalDefaultCostPerMonth)
                <br>inkl. Tanken: {{ decimal($vehicle->calc->totalDefaultCostPerMonth, 2) }} €
            @endif
        </p>

        @if($vehicle->calc->totalDefaultCostPerKilometer)
            <p>
                Vergleichspreis pro km<br>
                inkl. Tanken: {{ decimal($vehicle->calc->totalDefaultCostPerKilometer, 2) }} €
            </p>
        @endif
    </x-slot>

</x-cars::sortings.master>
