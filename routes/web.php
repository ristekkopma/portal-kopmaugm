<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/portal');
});

Route::get('/login', function () {
    return redirect('/portal/login');
})->name('login');

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'application' => config('app.name'),
    ]);
})->name('health');

Route::middleware(['auth'])->group(function () {
    Route::resource('events', EventController::class)->except('show');
});
