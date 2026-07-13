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

Route::middleware('auth')->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/event/{event:slug}', [EventController::class, 'show'])->name('events.show');
    Route::post('/event/{event:slug}/follow', [EventController::class, 'toggleFollow'])->name('events.follow');
    Route::post('/event/{event:slug}/review', [EventController::class, 'saveReview'])->name('events.review');
});
