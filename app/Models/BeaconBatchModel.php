<?php

namespace Iote\Models;

class BeaconBatchModel extends BaseModel {
	protected $collection = 'beacon_batchs';

	public function toArray() {
		$array = parent::toArray();
		$array['user'] = $this->user;
		$array['beacons'] = $this->beacons;
		$array['metadata'] = $this->metadata;
		return $array;
	}

	public function getBeaconsAttribute($attr) {
		return $attr ?: [];
	}

	public function getMetadataAttribute($attr) {
		return $attr ?: [];
	}
}