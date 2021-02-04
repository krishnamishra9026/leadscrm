<?php

Route::middleware('auth:api')->group(function () 
{
	// Route::get('customer/logout', 'Api\Customer\AuthController@logout');

});

Route::group(['middleware' => ['cors', 'json.response']], function () {
	Route::post('save-lead', 'Api\LeadUserController@store');
	Route::post('update-lead-status', 'Api\LeadUserController@update');
});