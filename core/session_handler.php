<?php namespace Core;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Session_Handler extends \SessionHandler
{
	private static $cipher = MCRYPT_BLOWFISH;
	private static $mode = MCRYPT_MODE_ECB;

	public function read($session_id)
	{
		return mcrypt_decrypt(
			self::$cipher, \Config::$encryptionKey, parent::read($session_id),
			self::$mode
		);
	}

	public function write($session_id, $session_data)
	{
		return parent::write(
			$session_id,
			mcrypt_encrypt(
				self::$cipher, \Config::$encryptionKey, $session_data,
				self::$mode
			)
		);
	}

	public function destroy($session_id)
	{
		setcookie(
			session_name(),
			NULL,
			time() - 172800,
			ini_get('session.cookie_path'),
			ini_get('session.cookie_domain')
		);
		return parent::destroy($session_id);
	}
}
