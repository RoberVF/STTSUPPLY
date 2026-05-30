<?php

use App\Livewire\ProductCatalog;
use Illuminate\Support\Facades\Route;

// Ruta raíz: carga todo el catálogo limpio
Route::get('/', ProductCatalog::class)->name('home');

// Ruta SEO por liga: carga el catálogo filtrado por la liga que venga en la URL
Route::get('/catalog/{league}', ProductCatalog::class)->name('catalog.league');