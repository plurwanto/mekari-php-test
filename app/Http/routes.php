<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', 'ToDoController@index');
Route::post('/todo-save', 'ToDoController@save');
Route::post('/todo-list', 'ToDoController@getTodoById');
Route::post('/todo-delete', 'ToDoController@destroy');