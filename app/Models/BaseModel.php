<?php

namespace Iote\Models;

use Jenssegers\Mongodb\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class BaseModel extends Moloquent {
	use SoftDeletes;
	public $timestamps = true;
	public $incrementing = false;
	protected $guarded = ['_id'];
	protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}