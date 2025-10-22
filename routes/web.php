<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/login', function () {
    // arahkan otomatis ke login portal
    return redirect('/portal/login');
})->name('login');




