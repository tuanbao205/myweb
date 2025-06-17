<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ListController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return redirect()->route('calendar.layout');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/calendar', [EventController::class, 'index'])->name('calendar.layout');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');

    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/task-lists', [ListController::class, 'store'])->name('task-lists.store');
    Route::resource('tasks', TaskController::class)->except(['show']);
    Route::resource('task-lists', ListController::class)->only(['store', 'update', 'destroy']);
});




require __DIR__.'/auth.php';

