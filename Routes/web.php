<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::prefix('cars')->name('cars.')->group(function () {
        require 'cars.php';
    });
});

Route::middleware(['referer.header', 'js.header', 'cache.header'])->group(function () {
    Route::prefix('js')->name('js.')->group(function () {
        require 'js.php';
    });
});
