<?php

namespace Iote\Http\Controllers;

use Validator;
use Illuminate\Http\Request;

use Iote\Models\UserModel;
use Iote\Models\BeaconModel;

class BeaconController extends BaseController {

	/*********************************
	 * Returns a list of beacons
	 * 	Allowable query params are `id`, `batch`, `deleted` */
	public function getIndex(Request $request) { // AUTHENTICATION REQUIRED
		if (is_null($this->user)) {
			return $this->makeUnauthorized();
		}

		$beacons = new BeaconModel;
		$attachedPrimarySearchClause = false;

		$id = $request->input('id');
		if (!is_null($id)) {
			$beacons = $beacons->where('_id', $id);
			$attachedPrimarySearchClause = true;
		}

		$batch = $request->input('batch');
		if (!is_null($batch)) {
			$beacons = $beacons->where('batch', $batch);
			$attachedPrimarySearchClause = true;
		}

		$deleted = $request->input('deleted');
		if (!is_null($deleted)) {
			$beacons = $beacons->onlyTrashed();
		}

		if ($attachedPrimarySearchClause == false) {
			$beacons = $beacons->where('id', 0);
		}

		return $this->makeSuccess("Beacons matching specified params", $beacons->get());
	}
}