<?php namespace Core;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Session
{
	public static function start()
	{
		session_set_cookie_params(
			\Config::$sessionLifetime,
			\Config::$base,
			\Config::$domain,
			TRUE
		);
		if (\Config::$sessionLifetime > 0)
			ini_set('session.gc_maxlifetime ', \Config::$sessionLifetime);
		ini_set('session.hash_function', 'ripemd320');
		session_name(\Config::$cookieName.'SESSION');
		session_set_save_handler(new Session_Handler);

		session_start();
		self::protect();
	}

	private static function protect()
	{
		if ( ! isset($_SESSION['user_agent']))
			$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
			session_destroy();
			http_response_code(400);
			exit('User agent changed. We are done.');
		}
	}


	public static function authenticated()
	{
		return isset($_SESSION['user_id'])
			&& is_numeric($_SESSION['user_id']);
	}
}
