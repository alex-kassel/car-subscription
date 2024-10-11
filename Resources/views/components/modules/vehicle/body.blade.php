<div class="row">
    <div class="card-body">
        <h5 class="card-title">
            <a href="{{ $vehicle->url }}" target="_blank">{{ $vehicle->title }}</a>
        </h5>

        {{ $vehicle->transmission }} •
        {{ $vehicle->power_hp }} PS ({{ intval($vehicle->power_hp/1.35962) }} KW)  •
        {{ $vehicle->drivetrain }}<br>

        {{ $vehicle->condition }}
        @if($vehicle->current_kilometers > 0)
            • {{ number_format($vehicle->current_kilometers, 0, ',', '.') }} km
        @endif
        @if($vehicle->first_registration)
            • EZ {{ substr($vehicle->first_registration, 0, 4) }}
        @endif
        <br>

        {{ $vehicle->fuel }}
        @if($vehicle->consumption > 0)
            • {{ number_format($vehicle->consumption, 1, ',', '.') }}
            {{ $vehicle->fuel == 'Elektro' ? 'KW' : 'Liter' }} je 100 km
        @endif
        <br>
        <br>

        @if($vehicle->listing_price > 0)
            Listenpreis: {{ number_format($vehicle->listing_price, 0, ',', '.') }} €
        @endif
        <br>
        Kategorie: {{ $vehicle->category }}<br>

        {{ $vehicle->plan->duration }} Mon •
        {{ $vehicle->plan->kilometers }} km/Mon •
        {{ number_format($vehicle->plan->price, 2, ',', '.') }} €/Mon
    </div>
</div>
