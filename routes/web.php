<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['prefix' => 'contacts'], function () {
    Route::get('/', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/create', [ContactController::class, 'create'])->name('contacts.create');
    Route::post('/', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('/{id}', [ContactController::class, 'show'])->name('contacts.show');
    Route::get('/{id}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::put('/{id}', [ContactController::class, 'update'])->name('contacts.update');
    Route::delete('/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::get('/search', [ContactController::class, 'search'])->name('contacts.search');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
