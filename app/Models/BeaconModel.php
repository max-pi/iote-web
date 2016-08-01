<?php

namespace Iote\Models;

class BeaconModel extends BaseModel {
	protected $collection = 'beacons';

	public function toArray() {
		$array = parent::toArray();
		$array['batch'] = $this->batch;
		$array['users'] = $this->users;
		$array['pings'] = $this->pings;
		$array['metadata'] = $this->metadata;
		return $array;
	}

	public function getUsersAttribute($attr) {
		return BaseModel::castArray($attr);
	}

	public function getPingsAttribute($attr) {
		return BaseModel::castArray($attr);
	}

	public function getMetadataAttribute($attr) {
		return BaseModel::castArray($attr);
	}

	public function ping($coordinates, $reporterId, $appId) {
		$reporter = UserModel::find($reporterId);
		if (is_null($reporter)) {
			return false;
		}

		$this->push('pings', [
			'timestamp' => date('c'),
			'coordinates' => $coordinates,
			'reporterId' => $reporter->_id,
			'appId' => $appId,
		]);

		return true;
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