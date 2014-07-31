<?php namespace Controllers;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Js
{
	public static function handle()
	{
		$which = implode('/', array_slice(\Core\Router::$uriParts, 1));

		$base = realpath(BASEPATH.'js').DIRECTORY_SEPARATOR;
		$file = $base.$which;
		if (
			strpos(realpath($file), $base) !== 0
			|| ! file_exists($file)
			|| ! preg_match('/.js$/', $file)
		) http_response_code(404) && exit;

		\Core\Headers::cache('text/javascript; charset=utf-8');

		readfile($file);
	}
}
