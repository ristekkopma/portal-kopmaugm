<?php

use App\Enums\UserRole;
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

Route::get('/events', function () {
    $route = in_array(auth()->user()->role, [
        UserRole::SuperAdmin,
        UserRole::Admin,
    ], true)
        ? 'filament.admin.resources.events.index'
        : 'filament.portal.resources.events.index';

    return redirect()->route($route);
})->middleware('auth')->name('events.index');
