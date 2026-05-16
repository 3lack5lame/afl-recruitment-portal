<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/apply', \App\Livewire\RecruitmentForm::class)->name('apply');
});
Route::get('/test-login', function() {
    $credentials = ['email' => 'admin@afl.gov.lr', 'password' => 'password123'];
    if (auth()->attempt($credentials)) {
        return '✅ LOGIN SUCCESSFUL! You are now logged in. <a href="/dashboard">Go to Dashboard</a>';
    }
    return '❌ LOGIN FAILED. Please check credentials.';
});
