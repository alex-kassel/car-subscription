@props(['vehicle', 'headLine'])

<x-shared::info-block>
    <x-slot name="infoText">
        <div class="p-3 pb-1 text-end">
            <p class="display-6">{{ $headLine }}</p>
            {{ $infoText }}
        </div>
    </x-slot>

    <x-slot name="infoButton">
        <div class="p-3 text-end">
            <button class="details-button btn btn-outline-primary" role="btn-switch"]>
                Volle Kalkulation <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </x-slot>

    <x-slot name="detailsText">
        <div class="p-3">
            @foreach($vehicle->calc as $key => $value)
                {{ $key }}: {{ $value }}<br>
            @endforeach
        </div>
    </x-slot>

    <x-slot name="detailsButton">
        <div class="p-3">
            <button class="back-button btn btn-outline-secondary" role="btn-switch">
                <i class="bi bi-arrow-left"></i>
            </button>

            <x-cars::modals.product-details button="Modal" btnClass="btn-outline-danger" />
        </div>
    </x-slot>
</x-shared::info-block>
