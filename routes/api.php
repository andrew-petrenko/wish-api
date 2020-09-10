<?php

use Illuminate\Support\Facades\Route;

Route::post('/auth/register', 'AuthController@register');
Route::post('/auth/login', 'AuthController@login');

/**
 * @see \App\Http\Middleware\JwtMiddleware
 */
Route::middleware('auth.jwt')->group(function () {
    Route::get('/wishes', 'WishController@index');
    Route::post('/wishes', 'WishController@create');
    Route::patch('/wishes/{uuid}', 'WishController@update');
    Route::patch('/wishes/{uuid}/goal-amount', 'WishController@changeGoal');
    Route::patch('/wishes/{uuid}/deposit', 'WishController@chargeDeposit');
    Route::delete('/wishes/{uuid}', 'WishController@delete');
});
