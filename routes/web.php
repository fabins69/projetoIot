<?php

use App\Http\Controllers\AmbienteController;
use App\Http\Controllers\SensorController;
use App\Livewire\Dashboard\Dashboard;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');
Route::get('/dashboard', Dashboard::class)->name('dashboard');
Route::resource('ambientes', AmbienteController::class)->except('show');
Route::resource('sensores', SensorController::class)->except('show')->parameters(['sensores' => 'sensor']);
