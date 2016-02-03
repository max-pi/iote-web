<?php

namespace Iote\Http\Middleware;

use Hash;
use Closure;
use Illuminate\Http\Request;

use Iote\Models\UserModel;
use Iote\Models\ContactModel;

class Authenticate {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next, $guard = null) {
		$username = $request->headers->get('php-auth-user');
		$password = $request->headers->get('php-auth-pw');

		$user = null;
		if (ContactModel::isEmail($username)) {
			$user = UserModel::where('emails', $username)->first();
		} elseif (ContactModel::isPhone($username)) {
			$user = UserModel::where('phones', $username)->first();
		}

		if (!is_null($user)) {
			if (Hash::check($password, $user->password)) {
				$request->session()->put('user', $user);
			}
		}

		return $next($request);
	}
}
