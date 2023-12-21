<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodosController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

// Users
Route::middleware('auth:api')
    ->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('user', [UsersController::class, 'show'])->name('users.show');
    });

// Todos
Route::middleware('auth:api')
    ->as('todos.')
    ->group(function () {
        Route::get('todos', [TodosController::class, 'index'])->name('index');
        Route::post('todos', [TodosController::class, 'store'])->name('store');
        Route::patch('todos/check-all', [TodosController::class, 'updateAll'])->name('check-all');
        Route::patch('todos/{todo}', [TodosController::class, 'update'])->name('update');
        Route::delete('todos/delete-completed', [TodosController::class, 'destroyCompleted'])->name('delete-completed');
        Route::delete('todos/{todo}', [TodosController::class, 'destroy'])->name('destroy');
    });
