<?php

use App\Http\Controllers\Api\CityController;
use Illuminate\Support\Facades\Route;

Route::get('cities/{country}', [CityController::class, 'getCities']);
