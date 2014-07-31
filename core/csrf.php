<?php namespace Core;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class CSRF
{
	public static function name()
	{
		return \Config::$cookieName.'CSRF';
	}

	public static function handle()
	{
		if ( ! isset($_COOKIE[self::name()])) self::set();
		self::verify();
	}

	public static function set()
	{
		setcookie(
			self::name(),
			md5(openssl_random_pseudo_bytes(64)),
			time() + 172800,
			\Config::$base,
			\Config::$domain,
			TRUE
		);
	}

	public static function verify()
	{
		if ( ! in_array(
			$_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE', 'PATCH']
		)) return;
		if ( ! isset($_COOKIE[self::name()], $_SERVER['HTTP_X_CSRF_TOKEN']))
			self::fail();
		if ($_COOKIE[self::name()] !== $_SERVER['HTTP_X_CSRF_TOKEN'])
			self::fail();
		self::set();
	}

	public static function fail()
	{
		http_response_code(400) && exit('CSRF failure');
	}
}
