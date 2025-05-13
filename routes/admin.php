<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Hola dezde el administrador';
})->name('admin.dashboard');