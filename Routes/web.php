<?php

use Illuminate\Support\Facades\Route;

Route::prefix('cars')->name('cars.')->middleware('web')->group(function () {
    require 'cars.php';
});
