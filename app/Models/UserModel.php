<?php

namespace Iote\Models;

class UserModel extends BaseModel {
	protected $collection = 'users';
	protected $hidden = ['password'];
}