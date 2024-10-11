@foreach($vehicles as $vehicle)
<div class="card">
    <div class="row">
        <div class="col-lg-4">
            <x-cars::modules.vehicle.image :vehicle="$vehicle" />
        </div>
        <div class="col-lg-4">
            <x-cars::modules.vehicle.body :vehicle="$vehicle" />
        </div>
        <div class="col-lg-4">
            <x-cars::modules.vehicle.calc :vehicle="$vehicle" :parameters="$parameters" />
        </div>
    </div>
</div>
@endforeach
