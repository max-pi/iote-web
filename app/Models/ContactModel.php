<?php

namespace Iote\Models;

class ContactModel extends BaseModel {
	protected $collection = 'contacts';

	public static function isEmail($string) {
		if (filter_var($string, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		return false;
	}

	public static function isPhone($string) {
		if (8 < strlen($string) || strlen($string) > 15) {
			return false;
		}

		$characters = explode('', $string);

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
}