<?php

namespace Iote\Http\Controllers;

use Validator;
use Illuminate\Http\Request;

use Iote\Models\UserModel;
use Iote\Models\ContactModel;

class UserController extends BaseController {

	/*********************************
	 * Returns active user if no query params are specified
	 * 	Allowable query params are `id`, `contact` */
	public function getIndex(Request $request) { // AUTHENTICATION REQUIRED
		if (is_null($this->user)) {
			return $this->makeUnauthorized();
		}

		$id = $request->input('id');
		if (!is_null($id)) {
			return $this->makeSuccess("User with specified id", UserModel::find($id));
		}

		$contact = $request->input('contact');
		if (!is_null($contact)) {
			if (ContactModel::isEmail($contact)) {
				return $this->makeSuccess("User with specified email", UserModel::where('emails', $contact)->first());
			}

			if (ContactModel::isPhone($contact)) {
				return $this->makeSuccess("User with specified phone", UserModel::where('phones', $contact)->first());
			}
		}

		return $this->makeSuccess("Currently active user", $this->user);
	}

	/*********************************
	 * Starts process for adding contact to current user
	 * 	Required params are `contact` */
	public function postContact() { // AUTHENTICATION REQUIRED
		return $this->makeSuccess("sending verification messages");
	}

	/*********************************
	 * Creates new user object and sends verification messages
	 * 	Required params are `contact` and `password` */
	public function postRegister(Request $request) { // AUTHENTICATION OPTIONAL
		$input = array(); $rules = array();
		$input['contact'] = $request->input('contact');
		$rules['contact'] = 'required|ephone';

		$input['password'] = $request->input('password');
		$rules['password'] = 'required|string|alpha_num|min:8';

		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			$messages = $validator->messages()->all();
			return $this->makeError($messages[0]);
		}

		$user = UserModel::create([
			'password' => $input['password']
		]);

		$contact = ContactModel::firstOrCreate([
			'contact' => $input['contact']
		])->recordUserAttempt($user->_id);

		return $this->makeSuccess("User registered successfully with pending verification", $user);
	}

	/*********************************
	 * Resends verification mail for a contact
	 * 	Required params are `contact` and `user` */
	public function postResendVerificationCode() { // AUTHENTICATION OPTIONAL
		$input = array(); $rules = array();
		$input['contact'] = $request->input('contact');
		$rules['contact'] = 'required|ephone';

		$input['user'] = $request->input('user');
		$rules['user'] = 'required|string';

		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			$messages = $validator->messages()->all();
			return $this->makeError($messages[0]);
		}

		$contact = ContactModel::where('contact', $input['contact'])->first();
		$matchingAttempt = null;
		foreach ($contact->attempts as $attempt) {
			if ($attempt['user'] == $input['user']) {
				$matchingAttempt = $attempt;
				break;
			}
		}

		if (is_null($matchingAttempt)) {
			return $this->makeUnauthorized();
		}

		$contact->sendVerificationMail($matchingAttempt['code']);
		return $this->makeSuccess("Verification code sent successfully");
	}

	/*********************************
	 * Confirms an email or phone record for a user
	 * 	Required params are `contact` and `code` */
	public function postVerify() { // AUTHENTICATION OPTIONAL
		$input = array(); $rules = array();
		$input['contact'] = $request->input('contact');
		$rules['contact'] = 'required|ephone';

		$input['code'] = $request->input('code');
		$rules['code'] = 'required|string';

		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			$messages = $validator->messages()->all();
			return $this->makeError($messages[0]);
		}

		$contact = ContactModel::where('contact', $input['contact'])->first();
		if (is_null($contact)) {
			return $this->makeError("Specified contact to verify does not exist");
		}

		$userId = $contact->retrieveUserIdByAttemptCode($input['code'], true);
		if (is_null($userId)) {
			return $this->makeError("Specified code failed to verify contact");
		}

		$user = UserModel::find($userId);
		if ($contact->is_email) {
			$user->push('emails', $contact->contact);
		}

		if ($contact->is_phone) {
			$user->push('phones', $contact->contact);
		}

		return $this->makeSuccess("Contact verified successfully for user", $user);
	}
}