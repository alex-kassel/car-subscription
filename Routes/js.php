<?php

use CarSubscription\DomainService;
use CarSubscription\Enums\Condition;
use CarSubscription\Enums\DrivetrainType;
use CarSubscription\Enums\FuelType;
use CarSubscription\Enums\TransmissionType;
use CarSubscription\Enums\VehicleType;
use CarSubscription\Repositories\VehicleRepository;
use CarSubscription\Services\Calc;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/{sorting}/cars.js', function ($sorting, DomainService $service, VehicleRepository $repository) {
    $vehicleIds = $repository->getVehicleIds([
        'sort' => $sorting,
        'km' => 1750,
    ]);
    $vehicles = $repository->getVehicles($vehicleIds)->keyBy('id');
    $vehicles =  collect($vehicleIds)->map(fn ($id) => $vehicles->get($id))->toJson();
    return sprintf('const vehicles=%s;console.log(vehicles);', $vehicles);
});
