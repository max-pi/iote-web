<?php

namespace Iote\Models;

use Hash;

class UserModel extends BaseModel {
	protected $collection = 'users';
	protected $hidden = ['password'];

	public function toArray() {
		$array = parent::toArray();
		$array['emails'] = $this->emails;
		$array['phones'] = $this->phones;
		$array['beacons'] = $this->beacons;
		$array['metadata'] = $this->metadata;
		return $array;
	}

	public function getEmailsAttribute($attr) {
		return $attr ?: [];
	}

	public function getPhonesAttribute($attr) {
		return $attr ?: [];
	}

	public function getBeaconsAttribute($attr) {
		return $attr ?: [];
	}

	public function getMetadataAttribute($attr) {
		return $attr ?: [];
	}

	public function setPasswordAttribute($value) {
		$this->attributes['password'] = Hash::make($value);
	}
}