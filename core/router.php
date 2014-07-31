<?php namespace Core;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Router
{
	public static $uri, $uriParts;
	private static $routes = array();
	private static $controller;


	public static function getURI()
	{
		$fullURI = substr($_SERVER['REQUEST_URI'], strlen(\Config::$base));
		$queryPosition = strpos($fullURI, '?');
		self::$uri = ($queryPosition === FALSE)
			? $fullURI
			: substr($fullURI, 0, $queryPosition);

		self::$uriParts = explode('/', trim(Router::$uri, '/'));
		self::$controller
			= basename(self::translate(current(self::$uriParts)));
		if (empty(self::$controller)) self::$controller = \Config::$root;
	}


	public static function controller($dir = '', $which = '')
	{
		$base = realpath(BASEPATH.'controllers').DIRECTORY_SEPARATOR;
		if (strpos(realpath($base.$dir), $base) !== 0) $dir = '';

		$which = basename($which);
		if (empty($which)) $which = self::$controller;
		$path = empty($dir) ? $which : $dir.DIRECTORY_SEPARATOR.$which;

		// emulate default autoloader
		if ( ! file_exists($base.$path.'.php')) return;

		call_user_func(
			'\\'.str_replace(
				DIRECTORY_SEPARATOR, '\\',
				basename($base).DIRECTORY_SEPARATOR.$path
			).'::handle'
		);
		exit;
	}


	public static function translate($name)
	{
		return str_replace('-', '_', strtolower($name));
	}
}
