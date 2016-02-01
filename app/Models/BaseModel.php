<?php

namespace Iote\Models;

use Jenssegers\Mongodb\Model as Moloquent;
use Illuminate\Database\Schema\Builder;

class BaseModel extends Moloquent {
	public $incrementing = false;

	public function __construct($attributes = array()) {
		parent::__construct($attributes);

		if (Schema::hasCollection($this->collection)) {
			Schema::create($this->collection, function($c) { });
		}
	}
}