<?php

namespace Iote\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BaseController extends Controller {
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
