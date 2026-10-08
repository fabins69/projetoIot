<?php

use App\Livewire\Ambientes\Form as AmbienteForm;
use App\Livewire\Ambientes\Index as AmbientesIndex;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Sensores\Form as SensorForm;
use App\Livewire\Sensores\Index as SensoresIndex;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');
Route::get('/dashboard', Dashboard::class)->name('dashboard');

Route::get('/ambientes', AmbientesIndex::class)->name('ambientes.index');
Route::get('/ambientes/create', AmbienteForm::class)->name('ambientes.create');
Route::get('/ambientes/{ambiente}/edit', AmbienteForm::class)->name('ambientes.edit')->whereNumber('ambiente');

Route::get('/sensores', SensoresIndex::class)->name('sensores.index');
Route::get('/sensores/create', SensorForm::class)->name('sensores.create');
Route::get('/sensores/{sensor}/edit', SensorForm::class)->name('sensores.edit')->whereNumber('sensor');
