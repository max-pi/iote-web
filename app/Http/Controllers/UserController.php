<?php

namespace Iote\Http\Controllers;

use Iote\Models\UserModel;
use Iote\Models\ContactModel;

class UserController extends BaseController {

	/*********************************
	 * Returns active user if no query params are specified
	 * 	Allowable query params are `id`, `email`, `phone` */
	public function getIndex() { // AUTHENTICATION REQUIRED
		return $this->makeSuccess(
			"OK",
			UserModel::find("56a819f8744d8446113393e7")
			//UserModel::where('emails', 'cheongwillie@gmail.com')->first()
		);
	}

	/*********************************
	 * Starts process for adding contact to current user
	 * 	Required params are [`email` OR `phone`] */
	public function postContact() { // AUTHENTICATION REQUIRED
		return $this->makeSuccess("sending verification messages");
	}

	/*********************************
	 * Creates new user object and sends verification messages
	 * 	Required params are [`email` OR `phone`] and `password` */
	public function postRegister() { // AUTHENTICATION OPTIONAL
		return $this->makeSuccess("registering");
	}

	/*********************************
	 * Confirms an email or phone record for a user
	 * 	Required params are [`email` OR `phone`] and `confirmationKey` */
	public function postConfirm() { // AUTHENTICATION OPTIONAL
		return $this->makeSuccess("confirming user");
	}
}