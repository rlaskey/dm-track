<?php namespace Core;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Controller
{
	public static function method($class, $offset = 1)
	{
		$method = isset(\Core\Router::$uriParts[$offset])
			? \Core\Router::$uriParts[$offset] : NULL;
		$function = $class.'::'.\Core\Router::translate($method);
		if ( ! is_callable($function)) return FALSE;

		call_user_func_array(
			$function,
			array_slice(\Core\Router::$uriParts, $offset + 1)
		);
		return TRUE;
	}


	public static function nested($class, $offset = 1)
	{
		if ( ! isset(\Core\Router::$uriParts[$offset])) return;
		$next = \Core\Router::$uriParts[$offset];

		$parts = array_slice(explode('\\', $class), 1);
		$path = strtolower(implode(DIRECTORY_SEPARATOR, $parts));
		\Core\Router::controller($path, $next);
	}
}
