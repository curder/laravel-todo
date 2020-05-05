<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('todos', 'TodosController@index');
Route::post('todos', 'TodosController@store');
Route::patch('todos/check-all', 'TodosController@updateAll');
Route::patch('todos/{todo}', 'TodosController@update');
Route::delete('todos/delete-completed', 'TodosController@destroyCompleted');
Route::delete('todos/{todo}', 'TodosController@destroy');
