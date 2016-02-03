<?php

namespace Iote\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class BaseController extends Controller {

	public function __construct(Request $request) {
		$this->user = $request->session()->get('user');
	}

	public function makeLanding() {
		return view('welcome');
	}

	public function makeUnauthorized() {
		return $this->makeError('User not authorized');
	}

	public function makeError($content="", $status=400) {
		if ( is_string($content) ) {
			$content = array(
				"message" => $content
			);
		}

		$response = new Response($content, $status);
		$response->header('Content-Type', 'application/json');
		return $response;
	}

	public function makeSuccess($content="", $data=null) {
		if ( is_string($content) ) {
			$content = array(
				"message" => $content,
				"response" => $data
			);
		}

		$response = new Response($content, 200);
		$response->header('Content-Type', 'application/json');
		return $response;
	}
}
