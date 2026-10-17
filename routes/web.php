<?php

use Illuminate\Support\Facades\Route;


Route::get('/', [\App\Http\Controllers\RecordCollectionController::class, 'index']);

