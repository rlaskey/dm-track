<?php namespace Core;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Headers
{
	public static function cache($contentType = 'text/plain')
	{
		header_remove('Expires');
		header_remove('Pragma');
		header('Cache-Control: private, max-age=31415926');
		header('Content-Type: '.$contentType);
	}
}
