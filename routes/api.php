<?php

use Illuminate\Support\Facades\Route;

Route::post('/auth/register', 'AuthController@register');
Route::post('/auth/login', 'AuthController@login');

/**
 * @see \App\Http\Middleware\JwtMiddleware
 */
Route::group(['middleware' => ['auth.jwt']], function () {

});
