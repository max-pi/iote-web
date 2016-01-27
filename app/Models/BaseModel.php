<?php

namespace Iote\Models;

use Jenssegers\Mongodb\Model as Moloquent;

class BaseModel extends Moloquent {
	public $incrementing = false;
}