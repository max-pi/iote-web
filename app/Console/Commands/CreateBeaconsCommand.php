<?php

namespace Iote\Console\Commands;

use Mail;
use Validator;
use Illuminate\Console\Command;
use Iote\Models\UserModel;
use Iote\Models\BeaconModel;
use Iote\Models\BeaconBatchModel;

class CreateBeaconsCommand extends Command {
	protected $signature = 'beacons:create
							{size : The number of beacons to create in this batch }
							{--u|user= : Attach all beacons created in this batch to a valid userId}';
	protected $description = 'Creates a specified number of beacons and saves the IDs to a batch';

	public function __construct() {
		parent::__construct();
	}

	public function handle() {
		$input = array(); $rules = array();
		$input['size'] = $this->argument('size');
		$rules['size'] = 'required|numeric|integer|min:1|max:5000';

		$input['user'] = $this->option('user');
		$rules['user'] = 'exists:users,_id';

		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			$messages = $validator->messages()->all();
			return $this->error($messages[0]);
		}

		$bar = $this->output->createProgressBar($input['size'] + 12);

		$beaconIds = array();
		$user = UserModel::find($input['user']);
		$batch = BeaconBatchModel::create([
			'user' => isset($user->_id) ? $user->_id : null
		]);

		$bar->advance();
		$this->line("Batch [".$batch->_id."] initialized");

		for ($i=0; $i < $input['size']; $i++) {
			$beacon = BeaconModel::create([
				'batch' => $batch->_id
			]);

			if (!is_null($user)) {
				$beacon->attach($user->_id);
			}

			$beaconIds[] = $beacon->_id;
			$bar->advance();
		}

		$bar->advance();
		$this->line("Finished creating [".count($beaconIds)."] beacon objects");

		$batch->update([
			'beacons' => $beaconIds
		]);

		$bar->advance();
		$this->line("Batch object updated with the generated beaconIds");

		try { // use built-in laravel mail client
			Mail::send('emails.batch_report', array(
				'batch' => $batch
			), function($message) {
				$message->to(env('MAIL_ADDRESS'), env('MAIL_NAME'))
						->subject('IOTE - Beacon Generation Report');
			});

			$bar->advance();
			$this->info("A batch report was sent to [".env('MAIL_ADDRESS')."]");

		} catch (Exception $e) {
			$this->line("Could not send the batch report to [".env('MAIL_ADDRESS')."]");
			$this->line("Keep track of this batch ID to retrieve batch details using other methods");
		}

		$bar->finish();
		return $this->info("Successfully created [".count($beaconIds)."] beacons for batch [".$batch->_id."]");
	}
}
