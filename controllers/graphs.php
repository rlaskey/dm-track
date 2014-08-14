<?php namespace Controllers;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Graphs
{
	public static function handle()
	{
		if ( ! \Core\Controller::method(__CLASS__))
			http_response_code(404) && exit;
	}


	public static function glucose()
	{
		$vars = array(
			'js' => array(
				'public/js/d3.js',
				'js/graphs/glucose.js'
			),
		);

		session_regenerate_id(TRUE) && session_write_close();
		\Core\View::show('blank', $vars);
	}


	public function glucose_svg()
	{
		$vars = ['js' => ['public/js/d3.js', 'js/graphs/glucose.js']];
		session_regenerate_id(TRUE) && session_write_close();
		\Core\View::show('svg', $vars);
	}


	public static function glucose_data()
	{
		$sData = \Core\DB::$db->prepare(
			'SELECT UNIX_TIMESTAMP(time) time, value, glucose_id '.
			'FROM glucose WHERE user_id = ? AND time >= ? AND time <= ?'
		);
		$sData->execute([
			$_SESSION['user_id'],
			strftime('%F %R', strtotime($_SESSION['range']['start'])),
			strftime('%F %R', strtotime($_SESSION['range']['stop']))
		]);
		header('Content-Type: text');
		$tab = "\t";
		echo 'hour', $tab, 'glucose';
		while ($record = $sData->fetch(\PDO::FETCH_OBJ)) {
			echo PHP_EOL,
				 strftime('%k', $record->time), '.',
				 100 * number_format(strftime('%M', $record->time) / 60, 2),
				 $tab,
				 $record->value;
		}
		$sData->closeCursor();
	}
}
