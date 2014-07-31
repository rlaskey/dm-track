<?php namespace Controllers;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Strtotime
{
	public static function handle()
	{
		$from = isset(\Core\Router::$uriParts[1])
			? \Core\Router::$uriParts[1] : NULL;
		$time = strtotime(urldecode($from));
		if ($time === FALSE)
			http_response_code(400) && exit('When?');
		echo strftime('%FT%R', $time);
	}
}
