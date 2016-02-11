<?php

Route::group(['middleware' => ['web']], function () {
	Route::get('/', 'BaseController@makeLanding');
	Route::controller('beacon', 'BeaconController');
	Route::controller('user', 'UserController');
});
