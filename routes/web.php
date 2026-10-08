<?php

use App\Livewire\Ambientes\AmbienteCreate;
use App\Livewire\Ambientes\AmbienteIndex;
use App\Livewire\Ambientes\Form as AmbienteForm;
use App\Livewire\Ambientes\Index as AmbientesIndex;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Sensores\Form as SensorForm;
use App\Livewire\Sensores\Index as SensoresIndex;
use App\Livewire\Sensores\SensorCreate;
use App\Livewire\Sensores\SensorIndex;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');
Route::get('/dashboard', Dashboard::class)->name('dashboard');

Route::get('/ambientes', AmbienteIndex::class)->name('ambientes.index');
Route::get('/ambientes/create', AmbienteCreate::class)->name('ambientes.create');
Route::get('/ambientes/{ambiente}/edit', AmbienteCreate::class)->name('ambientes.edit')->whereNumber('ambiente');

Route::get('/sensores', SensorIndex::class)->name('sensores.index');
Route::get('/sensores/create', SensorCreate::class)->name('sensores.create');
Route::get('/sensores/{sensor}/edit', SensorCreate::class)->name('sensores.edit')->whereNumber('sensor');
