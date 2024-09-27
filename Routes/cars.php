<?php

use CarSubscription\DomainService;
use Illuminate\Support\Facades\Route;

Route::get('/', function (DomainService $service) {
    return view('cars::index', compact('service'));
});
