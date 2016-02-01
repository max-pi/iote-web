<?php

namespace Iote\Models;

use Jenssegers\Mongodb\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class BaseModel extends Moloquent {
	public $incrementing = false;

	use SoftDeletes;
	protected $dates = ['deleted_at'];
}