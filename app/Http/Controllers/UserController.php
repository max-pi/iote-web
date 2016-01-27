<?php

namespace Iote\Http\Controllers;

use Iote\Models\UserModel;

class UserController extends BaseController {

	public function getIndex() {
		return $this->makeSuccess(
			"OK",
			UserModel::where('emails', 'cheongwillie@gmail.com')->first()
		);
	}

}