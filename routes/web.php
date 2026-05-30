<?php

use App\Livewire\ProductCatalog;
use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Home::class)->name('home');

Route::get('/catalog/{league?}', \App\Livewire\ProductCatalog::class)->name('catalog');