<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ShortLinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');



Route::get('/settings', function () {
    return view('profile.settings');
})->name('settings');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashBoardController::class, 'index'])->name('dashboard');

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');


    Route::get('/documents', [DocumentController::class, 'index'])->name('documents');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');


    Route::get('/shortlinks', [ShortLinkController::class, 'index'])->name('shortlinks');
    Route::post('/shortlinks', [ShortLinkController::class, 'store'])->name('shortlinks.store');
    Route::delete('/shortlinks/{shortLink}', [ShortLinkController::class, 'destroy'])->name('shortlinks.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
