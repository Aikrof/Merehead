<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([], function () {
    $controller = 'AuthController';

    Route::post('/signUp', "{$controller}@signUp");
    Route::post('/signIn', "{$controller}@signIn");
    Route::post('/logout', "{$controller}@logout");
});

Route::group([], function() {
    Route::get('/author', 'AuthorController@index');
    Route::get('/author/{uid}', 'AuthorController@view');
    Route::get('/book', 'BookController@index');
    Route::get('/book/{uid}', 'BookController@view');
});

Route::middleware('auth:api')->group(function() {
    Route::get('/user', 'UserController@index');
    Route::get('/user/{uid}', 'UserController@view');

    Route::get('/user/book', 'UserController@bookIndex');
    Route::get('/user/book/{uid}', 'UserController@bookView');

    Route::post('/book', 'BookController@create');
    Route::put('/book/{uid}', 'BookController@update');
    Route::delete('/book/{uid}', 'BookController@delete');
});
