<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Config
{
	# tr -cd [:alnum:] < /dev/urandom | head -c 32; echo
	public static $encryptionKey = '';

	public static $base = '/';
	public static $domain = 'example.org';
	public static $cookieName = 'dm';
	public static $sessionLifetime = 604800;
	public static $root = 'table';

	public static $defaultRange = array(
		'start' => '32 hours ago', 'stop' => 'tomorrow'
	);

	public static $db = array(
		'host' => 'localhost',
		'dbname' => 'dm',
		'username' => 'dm',
		'password' => '',
		'persistent' => FALSE
	);
}
