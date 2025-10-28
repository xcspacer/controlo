<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\FuelController;
use App\Http\Controllers\FuelRequestController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('stations', StationController::class);
    Route::resource('fuels', FuelController::class);
    Route::resource('users', UserController::class);
    Route::get('/surveys/{survey}/logs', [SurveyController::class, 'logs'])->name('surveys.logs');
    Route::get('/fuel-requests/create', [FuelRequestController::class, 'create'])->name('fuel-requests.create');
    Route::post('/fuel-requests', [FuelRequestController::class, 'store'])->name('fuel-requests.store');
    Route::get('/fuel-requests', [FuelRequestController::class, 'index'])->name('fuel-requests.index');
    Route::get('/fuel-requests/{fuelRequest}', [FuelRequestController::class, 'show'])->name('fuel-requests.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('surveys', SurveyController::class);
    Route::get('/api/stations/{station}/fuels', [SurveyController::class, 'getStationFuels']);
    Route::get('/surveys/station/{station}/current-month', [SurveyController::class, 'navigateToCurrentMonth'])->name('surveys.navigateToCurrentMonth');
    Route::post('/surveys/{survey}/add-fuel-load', [SurveyController::class, 'addFuelLoad'])->name('surveys.add-fuel-load');
    Route::get('/surveys/{survey}/fuel-loads', [SurveyController::class, 'fuelLoads'])->name('surveys.fuel-loads');
    Route::get('/surveys/{survey}/fuel-loads/{fuelLoad}/edit', [SurveyController::class, 'editFuelLoad'])->name('surveys.fuel-loads.edit');
    Route::put('/surveys/{survey}/fuel-loads/{fuelLoad}', [SurveyController::class, 'updateFuelLoad'])->name('surveys.fuel-loads.update');
    Route::delete('/surveys/{survey}/fuel-loads/{fuelLoad}', [SurveyController::class, 'destroyFuelLoad'])->name('surveys.fuel-loads.destroy');
    Route::get('surveys/{survey}/export', [SurveyController::class, 'export'])->name('surveys.export');
});

require __DIR__ . '/auth.php';