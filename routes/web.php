<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ShortLinkController;
use Illuminate\Support\Facades\Route;

// Tests

Route::get('/f/pass', function () {
    return view('auth.verify-email');
})->name('none');
Route::get('/r/pass', function () {
    return view('auth.reset-password');
})->name('none1');
Route::get('/c/p', function () {
    return view('auth.confirm-password');
})->name('none2');

// Main

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/terms-and-conditions', function () {
    return view('partials.terms');
})->name('terms-and-conditions');

Route::get('/privacy-policy', function () {
    return view('partials.privacy-policy');
})->name('privacy-policy');


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashBoardController::class, 'index'])->name('dashboard');
    Route::get('/mods/dashboard', [DashBoardController::class, 'index'])->name('mods.dashboard');

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::patch('/tasks/{task}/next-status', [TaskController::class, 'nextStatus'])->name('tasks.nextStatus');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');


    Route::get('/documents', [DocumentController::class, 'index'])->name('documents');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');


    Route::get('/shortlinks', [ShortLinkController::class, 'index'])->name('shortlinks');
    Route::post('/shortlinks', [ShortLinkController::class, 'store'])->name('shortlinks.store');
    Route::put('/shortlinks/{shortLink}', [ShortLinkController::class, 'update'])->name('shortlinks.update');
    Route::patch('/shortlinks/{shortLink}/expand', [ShortLinkController::class, 'expand'])->name('shortlinks.expand');
    Route::delete('/shortlinks/{shortLink}', [ShortLinkController::class, 'destroy'])->name('shortlinks.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::get('/settings/export', [SettingsController::class, 'export'])->name('settings.export');
    Route::post('/settings/logout-everywhere', [SettingsController::class, 'logoutEverywhere'])
        ->name('settings.logout.everywhere');
    Route::delete('/settings/account', [SettingsController::class, 'destroyAccount'])->name('settings.account.destroy');


    Route::get('/admin/dashboard', [AdminController::class, 'showAdmin'])->name('admin.dashboard');
    Route::put('/admin/users/{user}', [AdminController::class, 'update'])
        ->name('admin.users.update');
    Route::delete('/admin/users/{user}/data', [AdminController::class, 'deleteUserData'])
        ->name('admin.data.delete');

    Route::get('/admin/tools/export', [AdminController::class, 'exportAllData'])
        ->name('admin.tools.export');
    Route::post('/admin/tools/clear-cache', [AdminController::class, 'clearCache'])
        ->name('admin.tools.clearCache');
    Route::delete('/admin/tools/purge', [AdminController::class, 'purgeAllData'])
        ->name('admin.tools.purge');
});

Route::get('/link-expired', function () {
    return view('error.expired');
})->name('expired');

Route::get('/invalid-link', function () {
    return view('error.invalid');
})->name('invalid');

require __DIR__ . '/auth.php';

Route::get('/doc/{name}', [DocumentController::class, 'show'])->name('documents.public');

// web.php (near the bottom)
Route::get('/{alias}', [ShortLinkController::class, 'redirect'])
    ->name('shortlinks.redirect');


