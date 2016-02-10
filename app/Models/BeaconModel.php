<?php

namespace Iote\Models;

class BeaconModel extends BaseModel {
	protected $collection = 'beacons';

	public function toArray() {
		$array = parent::toArray();
		$array['batch'] = $this->batch;
		$array['users'] = $this->users;
		$array['metadata'] = $this->metadata;
		return $array;
	}

	public function getUsersAttribute($attr) {
		return BaseModel::castArray($attr);
	}

	public function getMetadataAttribute($attr) {
		return BaseModel::castArray($attr);
	}

	public function attach($userId) {
		$user = UserModel::find($userId);
		if (is_null($user)) {
			return false;
		}

		if (!in_array($this->_id, $user->beacons)) {
			$this->push('users', $user->_id);
			$user->push('beacons', $this->_id);
		}

		return true;
	}

	public function detach($userId) {
		$user = UserModel::find($userId);
		if (is_null($user)) {
			return false;
		}

		$this->pull('users', $user->_id);
		$user->pull('beacons', $this->_id);
		return true;
	}
}