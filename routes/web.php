<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::any('/{slug}', function () {
    return view('home');
});
