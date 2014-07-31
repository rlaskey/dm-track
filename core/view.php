<?php namespace Core;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class View
{
	public static function show($which, &$vars = NULL)
	{
		$base = realpath(BASEPATH.'views');
		$file = $base.DIRECTORY_SEPARATOR.$which.'.php';
		if (strpos(realpath($file), $base) !== 0 || ! file_exists($file))
			http_response_code(500) && exit('Missing some views..');

		if (is_array($vars)) extract($vars);

		ob_start();
		include $file;
		ob_end_flush();
	}
}
