<?php

use Illuminate\Support\Facades\Route;

Route::post('login', 'AuthController@login')->name('login');
Route::post('register', 'AuthController@register')->name('register');
Route::middleware('auth:api')->post('logout', 'AuthController@logout')->name('logout');
Route::middleware('auth:api')->get('user', 'UsersController@show')->name('users.show');

Route::middleware('auth:api')->as('todos.')->group(function () {
    Route::get('todos', 'TodosController@index')->name('index');
    Route::post('todos', 'TodosController@store')->name('store');
    Route::patch('todos/check-all', 'TodosController@updateAll')->name('check-all');
    Route::patch('todos/{todo}', 'TodosController@update')->name('update');
    Route::delete('todos/delete-completed', 'TodosController@destroyCompleted')->name('delete-completed');
    Route::delete('todos/{todo}', 'TodosController@destroy')->name('destroy');
});
