<?php
use App\Http\Controllers\AlbumController;
use Illuminate\Support\Facades\Route;

Route::resource('albums', \App\Http\Controllers\AlbumController::class);


