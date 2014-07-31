<?php namespace Controllers;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Range
{
	public static function handle()
	{
		$incoming = json_decode(file_get_contents('php://input'));

		foreach ($_SESSION['range'] AS $part => $value) {
			if ( ! isset($incoming->$part)) continue;

			$newValue = \Core\XSS::clean($incoming->$part);
			if (strtotime($newValue) === FALSE) http_response_code(400)
				&& exit('Sorry, could not sort out what time that was :/');

			$_SESSION['range'][$part] = $newValue;
		}

		echo json_encode($_SESSION['range']);
	}
}
