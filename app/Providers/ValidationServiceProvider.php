<?php

namespace Iote\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;

use Iote\Models\ContactModel;

class ValidationServiceProvider extends ServiceProvider {

	public function boot() {
		Validator::extend('ephone', function($attribute, $value, $parameters, $validator) {
			$isEmail = ContactModel::isEmail($value);
			$isPhone = ContactModel::isPhone($value);
			return ($isEmail || $isPhone);
		});

		Validator::replacer('ephone', function($message, $attribute, $rule, $parameters) {
			return "The ".$attribute." must be either an email or phone";
		});
	}

	public function register() {
		//
	}
}