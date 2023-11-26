<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return '<h1>Bienvenido a nuestro panel de administración</h1>';
})->name('admin.home');