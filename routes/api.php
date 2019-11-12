<?php

use Illuminate\Http\Request;

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

Route::namespace('Auth')->group(function () {

	Route::post('login', function () {
		$credentials = ['email' => request('email'), 'password' => request('password')];

		if (!Auth::attempt($credentials)) {
			abort(401);
		}
		return auth()->user()->api_token;
	});

	Route::post('register', 'RegisterController@register');
});

Route::middleware('auth:api')->group(function () {

	Route::get('refunds', 'RefundController@index');

	Route::post('refunds', 'RefundController@store');

	Route::patch('refunds/{refund}', 'RefundController@update');

	Route::delete('refunds/{refund}', 'RefundController@destroy');

	Route::patch('refunds/{refund}/approve', 'RefundController@approve');

	Route::get('people/{person}/refunds/{year}/{month}', 'RefundReportController@monthly');

	Route::get('people/{person}/export/refunds/{year}/{month}', 'RefundReportController@export');
});