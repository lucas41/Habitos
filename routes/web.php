<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AnalyticsController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/habit/{habit}/toggle', [DashboardController::class, 'toggle'])->name('habit.toggle');

Route::resource('habits', HabitController::class);
Route::resource('categories', CategoryController::class);
Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
