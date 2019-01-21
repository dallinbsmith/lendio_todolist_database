<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [
    'uses' => 'TodoController@index',
    'as' => 'todolist.index'
]);

// Fetch Container Routes
Route::get('/create', function () {
    return view('todolist.create');
})->name('todolist.create');

Route::get('/signup', function () {
    return view('auth.signup');
})->name('auth.signup');

Route::get('/login', function () {
    return view('auth.login');
})->name('auth.login');


// Login Routes to Database
Route::post('login/auth', [
    'uses' => 'AuthController@login',
    'as' => 'auth.loginpost'
]);

Route::post('signup/auth', [
    'uses' => 'AuthController@signup',
    'as' => 'auth.signuppost'
]);

// Get Individual Todo
Route::get('todo/{id}', [
    'uses' => 'TodoController@show',
    'as' => 'todolist.show'
]);

// API Calls
Route::group(['prefix' => 'todolist'], function() {

    Route::post('createpost/{token}', [
        'uses' => 'TodoController@store',
        'as' => 'todolist.createpost'
    ]);

    Route::get('edit/{id}', [
        'uses' => 'TodoController@edit',
        'as' => 'todolist.edit'
    ]);

    Route::post('update/{id}/{token}', [
        'uses' => 'TodoController@update',
        'as' => 'todolist.update'
    ]);

    Route::get('delete/{id}', [
        'uses' => 'TodoController@delete',
        'as' => 'todolist.delete'
    ]);

    Route::post('destroy/{id}', [
        'uses' => 'TodoController@destroy',
        'as' => 'todolist.destroy'
    ]);
});
