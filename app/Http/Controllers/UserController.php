<?php

namespace Iote\Http\Controllers;

use Validator;
use Illuminate\Http\Request;

use Iote\Models\UserModel;
use Iote\Models\ContactModel;
use Iote\Models\BeaconModel;

class UserController extends BaseController {

	/*********************************
	 * Returns a list of users
	 * Returns active user if no query params are specified
	 * 	Allowable query params are `id`, `contact` */
	public function getIndex(Request $request) { // AUTHENTICATION REQUIRED
		if (is_null($this->user)) {
			return $this->makeUnauthorized();
		}

		$id = $request->input('id');
		if (!is_null($id)) {
			return $this->makeSuccess("Users with specified id", UserModel::where('_id', $id)->get());
		}

		$contact = $request->input('contact');
		if (!is_null($contact)) {
			if (ContactModel::isEmail($contact)) {
				return $this->makeSuccess("Users with specified email", UserModel::where('emails', $contact)->get());
			}

			if (ContactModel::isPhone($contact)) {
				return $this->makeSuccess("Users with specified phone", UserModel::where('phones', $contact)->get());
			}
		}

		return $this->makeSuccess("This currently active user", array($this->user));
	}

	/*********************************
	 * Attaches metadata to the current user
	 * 	Required param is `metadata`, an object that can be of any shape */
	public function putMetadata(Request $request) { // AUTHENTICATION REQUIRED
		if (is_null($this->user)) {
			return $this->makeUnauthorized();
		}

		$input = array(); $rules = array();
		$input['metadata'] = $request->input('metadata');
		$rules['metadata'] = 'required|array';

		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			$messages = $validator->messages()->all();
			return $this->makeError($messages[0]);
		}

		$this->user->update($input);

		return $this->makeSuccess("Metadata attached successfully to user", $this->user);
	}

	/*********************************
	 * Attaches or detaches a beacon to the current user
	 *  Endpoint should be trailed with either `/attach` or `detach`
	 * 	Required param is `beacon`, the id of the beacon to work with */
	public function putBeacon(Request $request, $action = null) { // AUTHENTICATION REQUIRED
		if (is_null($this->user)) {
			return $this->makeUnauthorized();
		}

		$input = array(); $rules = array();
		$input['action'] = strtolower($action);
		$rules['action'] = 'required|in:attach,detach';

		$input['beacon'] = $request->input('beacon');
		$rules['beacon'] = 'required|exists:beacons,_id';

		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			$messages = $validator->messages()->all();
			return $this->makeError($messages[0]);
		}

		$beacon = BeaconModel::find($input['beacon']);
		if (is_null($beacon)) {
			return $this->makeError("Beacon does not exist");
		}

		if ($input['action'] == 'attach') {
			if (in_array($beacon->_id, $this->user->beacons)) {
				return $this->makeError("Beacon already belongs to user");
			}
			$beacon->attach($this->user->_id);
			return $this->makeSuccess("Beacon attached successfully to user", $this->user);
		}

		if ($input['action'] == 'detach') {
			if (!in_array($beacon->_id, $this->user->beacons)) {
				return $this->makeError("Beacon does not belong to user");
			}
			$beacon->detach($this->user->_id);
			return $this->makeSuccess("Beacon detached successfully from user", $this->user);
		}

		return $this->makeSuccess("Beacon was neither attached nor detached from user", $this->user);
	}

	/*********************************
	 * Starts process for adding contact to current user
	 * 	Required params are `contact` */
	public function postContact(Request $request) { // AUTHENTICATION REQUIRED
		if (is_null($this->user)) {
			return $this->makeUnauthorized();
		}

		$input = array(); $rules = array();
		$input['contact'] = $request->input('contact');
		$rules['contact'] = 'required|ephone';

		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			$messages = $validator->messages()->all();
			return $this->makeError($messages[0]);
		}

		$contact = ContactModel::firstOrCreate([
			'contact' => $input['contact']
		]);

		if ($contact->confirmed) {
			return $this->makeError("Contact already registered to an account");
		}

		$contact->recordUserAttempt($this->user->_id);

		return $this->makeSuccess("Contact registered for user with pending verification", $this->user);
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

		$contact = ContactModel::firstOrCreate([
			'contact' => $input['contact']
		]);

		if ($contact->confirmed) {
			return $this->makeError("Contact already registered to an account");
		}

		$user = UserModel::create([
			'password' => $input['password']
		])->first();

		$contact->recordUserAttempt($user->_id);

		return $this->makeSuccess("User registered successfully with pending verification", $user);
	}

	/*********************************
	 * Resends verification mail for a contact
	 * 	Required params are `contact` and `user` */
	public function postResendVerificationCode(Request $request) { // AUTHENTICATION OPTIONAL
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
		if (is_null($contact)) {
			return $this->makeError("Contact does not exist");
		}

		if ($contact->confirmed) {
			return $this->makeError("Contact already registered to an account");
		}

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
	public function postVerify(Request $request) { // AUTHENTICATION OPTIONAL
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
			return $this->makeError("Contact does not exist");
		}

		if ($contact->confirmed) {
			return $this->makeError("Contact already registered to an account");
		}

		$userId = $contact->retrieveUserIdByAttemptCode($input['code'], true);
		if (is_null($userId)) {
			return $this->makeError("Specified code failed to verify contact");
		}

		$user = UserModel::find($userId);
		if (is_null($user)) {
			return $this->makeError("Specified code does not belong to a valid user");
		}

		if ($contact->is_email) {
			$user->push('emails', $contact->contact);
		}

		if ($contact->is_phone) {
			$user->push('phones', $contact->contact);
		}

		return $this->makeSuccess("Contact verified successfully for user", $user);
	}
}