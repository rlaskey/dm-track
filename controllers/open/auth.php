<?php namespace Controllers\Open;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Auth
{
	public static function handle()
	{
		\Core\Controller::method(__CLASS__);
	}


	public static function login()
	{
		if (\Core\Session::authenticated())
			header('Location: '.\Config::$base) || exit;

		$vars = array(
			'js' => array(
				'https://login.persona.org/include.js',
				'public/js/auth/'.__FUNCTION__.'.js',
			),
		);
		\Core\View::show('auth/'.__FUNCTION__, $vars);
	}


	public static function logout()
	{
		$vars = array(
			'persona' => isset($_SESSION['persona'])
			? $_SESSION['persona'] : NULL
		);
		session_destroy();
		\Core\View::show('auth/'.__FUNCTION__, $vars);
	}


	public static function persona()
	{
		$c = curl_init();
		$audience = 'https://'.\Config::$domain.':443';
		curl_setopt_array($c, array(
			CURLOPT_SSL_VERIFYPEER => TRUE,
			CURLOPT_URL => 'https://verifier.login.persona.org/verify',
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_POST => 2,
			CURLOPT_POSTFIELDS => 'assertion='.urlencode($_POST['assertion']).
			'&audience='.urlencode($audience)
		));
		$result = curl_exec($c);
		if ($result === FALSE)
			http_response_code(500) && exit('Could not connect to Person :/');
		curl_close($c);

		$object = json_decode($result);
		if ($object->audience !== $audience)
			http_response_code(500) && exit('Wrong audience?!');
		if ($object->status !== 'okay')
			http_response_code(500) && exit('Persona status is not okay :/');

		$_SESSION['persona'] = $object->email;
		$_SESSION['range'] = \Config::$defaultRange;

		$sGetUser = \Core\DB::$db
			->prepare('SELECT * FROM user WHERE email = :email');
		$sGetUser->execute([':email' => $object->email]);
		$rGetUser = $sGetUser->fetchAll(\PDO::FETCH_OBJ);
		$sGetUser->closeCursor();
		if (count($rGetUser) === 1) {
			$user = current($rGetUser);
			$_SESSION['user_id'] = $user->user_id;
		} else {
			$user = new \stdClass;
			$user->email = $object->email;
			$_SESSION['user_id'] = \Models\User::set(NULL, $user);
		}
	}
}
