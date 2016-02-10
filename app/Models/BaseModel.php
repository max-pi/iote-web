<?php

namespace Iote\Models;

use Carbon\Carbon;
use Jenssegers\Mongodb\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class BaseModel extends Moloquent {
	use SoftDeletes;
	public $timestamps = true;
	public $incrementing = false;
	protected $guarded = ['_id'];

	public function getCreatedAtAttribute($attr) {
		return Carbon::parse($attr)->toIso8601String();
	}

	public function getUpdatedAtAttribute($attr) {
		return Carbon::parse($attr)->toIso8601String();
	}

	public function getDeletedAtAttribute($attr) {
		if (!is_null($attr)) { // undeleted objects have null deleted values
			return Carbon::parse($attr)->toIso8601String();
		}
	}


/* STATIC HELPER FUNCTIONS
 *******************************************/
	public static function castArray($attr) {
		return (is_array($attr)) ? $attr : [];
	}
}