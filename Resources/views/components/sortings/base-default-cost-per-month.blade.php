<x-cars::sortings.master  :vehicle="$vehicle" headLine="{{ decimal($vehicle->sortingValue, 0) }} €">

    <x-slot name="infoText">
        <p>Monatliche Rate<br>inkl. aller Kosten außer Tanken</p>
        <p>inkl. {{ decimal($vehicle->plan->kilometers, 0) }} km monatlich</p>

        <p>
            Kalkulatorische Kosten pro km<br>
            ohne Tanken: {{ decimal($vehicle->calc->baseDefaultCostPerKilometer, 2) }} €
            @if($vehicle->calc->totalDefaultCostPerKilometer)
                <br>inkl. Tanken {{ decimal($vehicle->calc->totalDefaultCostPerKilometer, 2) }} €
            @endif
        </p>

        @if($vehicle->calc->totalDefaultCostPerMonth)
            <p>
                Monatliche Kosten<br>
                inkl. Tanken: {{ decimal($vehicle->calc->totalDefaultCostPerMonth, 2) }} €
            </p>
        @endif
    </x-slot>

</x-cars::sortings.master>
