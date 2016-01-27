<?php

namespace Iote\Http\Controllers\V1;

use Iote\Http\Controllers\Controller as Controller;
use Iote\Models\UserModel;

class UserController extends Controller {

	public function getIndex() {
		dd(UserModel::where('emails', 'cheongwillie@gmail.com')->get());
	}

}