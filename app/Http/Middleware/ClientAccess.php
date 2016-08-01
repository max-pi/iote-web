<?php

namespace Iote\Http\Middleware;

use Hash;
use Closure;
use Illuminate\Http\Request;

use Iote\Models\UserModel;
use Iote\Models\ContactModel;

class ClientAccess {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next, $guard = null) {
		$token = $request->headers->get('X-Iote-Client-Token');

		if ($token != 'iote-top-secret-007') {
			abort(403);
		}

		return $next($request);
	}
}

