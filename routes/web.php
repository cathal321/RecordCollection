<?php
use App\Http\Controllers\AlbumController;
use Illuminate\Support\Facades\Route;


Route::get('/home', function () {
    return redirect()-> route('home');


});

Route::resource('albums', \App\Http\Controllers\AlbumController::class);

Route::get('/home', function () {
    return view('home');
});


Route::get('/login', function () {
    return view('login');


});
