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

	/*********************************
	 * Attaches metadata to the beacon
	 * 	Required param is `metadata`, an object that can be of any shape */
	public function putMetadata(Request $request, $beaconId = null) { // AUTHENTICATION REQUIRED
		if (is_null($this->user)) {
			return $this->makeUnauthorized();
		}

		$input = array(); $rules = array();
		$input['beacon'] = $beaconId;
		$rules['beacon'] = 'required|exists:beacons,_id';

		$input['metadata'] = $request->input('metadata');
		$rules['metadata'] = 'required|array';

		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			$messages = $validator->messages()->all();
			return $this->makeError($messages[0]);
		}

		$beacon = BeaconModel::find($input['beacon']);
		if (is_null($beacon)) {
			return $this->makeError("Beacon does not exist");
		}

		if (!in_array($beacon->_id, $this->user->beacons)) {
			return $this->makeError("Beacon does not belong to user");
		}

		$beacon->update([
			'metadata' => $input['metadata']
		]);

		return $this->makeSuccess("Metadata attached successfully to beacon", $beacon);
	}

	/*********************************
	 * Stores coordinates to a beacon from a reporting user
	 *  Endpoint should be trailed with a string that is the beaconId
	 * 	Required param are `coordinates` and `appId` */
	public function postPing(Request $request, $beaconId = null) { // AUTHENTICATION REQUIRED
		if (is_null($this->user)) {
			return $this->makeUnauthorized();
		}

		$input = array(); $rules = array();
		$input['beacon'] = $beaconId;
		$rules['beacon'] = 'required|exists:beacons,_id';

		$input['coordinates'] = $request->input('coordinates');
		$rules['coordinates'] = 'required|string';

		$input['appId'] = $request->input('appId');
		$rules['appId'] = 'required|string';

		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			$messages = $validator->messages()->all();
			return $this->makeError($messages[0]);
		}

		$beacon = BeaconModel::find($input['beacon']);
		if (is_null($beacon)) {
			return $this->makeError("Beacon does not exist");
		}

		$beacon->ping(
			$input['coordinates'],
			$this->user->_id,
			$input['appId']
		);

		return $this->makeSuccess("Location updated for this beacon", $beacon);
	}
}