<?php

use App\Livewire\Dashboard\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', Dashboard::class)->name('dashboard');
