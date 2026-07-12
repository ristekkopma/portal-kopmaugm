<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/portal');
});

Route::get('/login', function () {
    return redirect('/portal/login');
})->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/events', [EventController::class, 'index'])
        ->name('events.index');
});