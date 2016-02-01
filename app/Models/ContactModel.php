<?php

namespace Iote\Models;

class ContactModel extends BaseModel {
	protected $collection = 'contacts';

	public function toArray() {
		$array = parent::toArray();
		$array['contact'] = $this->contact;
		$array['attempts'] = $this->attempts;
		$array['confirmed_user'] = $this->confirmed_user;

		$array['confirmed'] = $this->confirmed;
		$array['is_email'] = $this->is_email;
		$array['is_phone'] = $this->is_phone;

		return $array;
	}

	public function getAttemptsAttribute($attr) {
		return $attr ?: [];
	}

	public function getConfirmedAttribute() {
		if (strlen($this->confirmed_user) > 2) {
			return true;
		}
		return false;
	}

	public function getIsEmailAttribute() {
		return ContactModel::isEmail($this->contact);
	}

	public function getIsPhoneAttribute() {
		return ContactModel::isPhone($this->contact);
	}


/* BAKED IN CONTACT VERIFICATION LOGIC
 *******************************************/
	public function recordUserAttempt($userId) {
		$code = ContactModel::generateCode();
		while (!is_null($this->retrieveUserIdByAttemptCode($code))) {
			$code = ContactModel::generateCode();
		}

		$this->push('attempts', [
			'code' => $code,
			'user' => $userId,
		]);

		// execute scripts for sending verification mail
	}

	public function retrieveUserIdByAttemptCode($code, $lockOnSuccess = false) {
		$userId = null;

		foreach ($this->attempts as $attempt) {
			if ($attempt['code'] == $code) {
				$userId = $attempt['user'];
			}
		}

		if (!$this->confirmed && $lockOnSuccess) {
			$this->confirmed_user = $userId;
			$this->save();
		}

		return $userId;
	}


/* STATIC HELPER FUNCTIONS
 *******************************************/
	public static function isEmail($string) {
		if (filter_var($string, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		return false;
	}

	public static function isPhone($string) {
		if (strlen($string) < 8 || strlen($string) > 15) {
			return false;
		}

		$characters = str_split($string);
		if ($characters[0] != '+') {
			return false;
		}

		for ($i=1; $i < count($characters); $i++) {
			if (!ctype_digit($characters[$i])) {
				return false;
			}
		}

		return true;
	}

	private static function generateCode($length = 6) {
		$alphabets = range('A','Z');
		$numbers = range('0','9');
		$final_array = array_merge($alphabets, $numbers);

		$password = '';

		while($length--) {
			$key = array_rand($final_array);
			$password .= $final_array[$key];
		}

		return $password;
	}
}