<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/sign-in', function () {
    return view('auth.sign-in');
})->name('sign-in');

Route::get('/sign-up', function () {
    return view('auth.sign-up');
})->name('sign-up');
