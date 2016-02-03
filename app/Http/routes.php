<?php

Route::group(['middleware' => ['web']], function () {
	Route::get('/', 'BaseController@makeLanding');
	Route::controller('user', 'UserController');
});
