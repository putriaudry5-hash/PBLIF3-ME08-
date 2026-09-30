<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login.pelanggan');
});

Route::get('/login/pelanggan', function () {
    return view('auth.login-pelanggan');
})->name('login.pelanggan');

Route::get('/login/internal', function () {
    return view('auth.login-internal');
})->name('login.internal');

Route::get('/pelanggan/dashboard', function () {
    return view('pelanggan.dashboard');
})->name('pelanggan.dashboard');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');