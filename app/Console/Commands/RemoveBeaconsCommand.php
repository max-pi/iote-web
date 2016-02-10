<?php

namespace Iote\Console\Commands;

use Mail;
use Validator;
use Illuminate\Console\Command;
use Iote\Models\UserModel;
use Iote\Models\BeaconModel;
use Iote\Models\BeaconBatchModel;

class RemoveBeaconsCommand extends Command {
	protected $signature = 'beacons:remove
							{batch : The batchId of the beacons to remove }';
	protected $description = 'Detaches and removes all beacons belonging to the specified batch';

	public function __construct() {
		parent::__construct();
	}

	public function handle() {
		$input = array(); $rules = array();
		$input['batch'] = $this->argument('batch');
		$rules['batch'] = 'required|exists:beacon_batchs,_id';

		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			$messages = $validator->messages()->all();
			return $this->error($messages[0]);
		}

		$countDeleted = 0;
		$batch = BeaconBatchModel::find($input['batch']);
		$bar = $this->output->createProgressBar(count($batch->beacons) + 12);

		$bar->advance();
		$this->line(" Retrieved [".$batch->_id."]");

		foreach ($batch->beacons as $beaconId) {
			$beacon = BeaconModel::find($beaconId);
			if (is_null($beacon)) {
				continue;
			}

			if ($beacon->batch != $batch->_id) {
				continue;
			}

			$beaconUsers = $beacon->users;
			foreach ($beaconUsers as $userId) {
				$beacon->detach($userId);
			}

			$beacon->delete();
			$countDeleted++;
			$bar->advance();
		}

		$bar->advance();
		$this->line(" Finished removing [".$countDeleted."] beacon objects");

		$batch->delete();

		$bar->advance();
		$this->line(" Deleted the specified batch object");

		try { // use built-in laravel mail client
			Mail::send('emails.batch_deleted_report', array(
				'batch' => $batch
			), function($message) {
				$message->to(env('MAIL_ADDRESS'), env('MAIL_NAME'))
						->subject('IOTE - Beacon Removal Report');
			});

			$bar->advance();
			$this->info(" A batch report was sent to [".env('MAIL_ADDRESS')."]");

		} catch (Exception $e) {
			$this->line(" Could not send the batch report to [".env('MAIL_ADDRESS')."]");
			$this->line(" Keep track of this batch ID to retrieve batch details using other methods");
		}

		$bar->finish();
		return $this->info(" Successfully removed [".$countDeleted."] beacons for batch [".$batch->_id."]");
	}
}
