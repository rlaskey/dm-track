<?php namespace Controllers;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Table
{
	public static function handle()
	{
		if (\Core\Controller::method(__CLASS__)) return TRUE;

		$vars = array(
			'js' => array(
				'public/js/zubb.js',
				'models/glucose-insulin',
				'js/table.js'
			),
			'asides' => ['inputs'],
		);
		session_regenerate_id(TRUE) && session_write_close();
		\Core\View::show('table', $vars);
	}


	public static function data()
	{
		header('Content-Type: text/javascript; charset=utf-8');

		$where = 'WHERE user_id = ? AND time >= ? AND time <= ?';
		$pWhere = array(
			$_SESSION['user_id'],
			strftime('%F %R', strtotime($_SESSION['range']['start'])),
			strftime('%F %R', strtotime($_SESSION['range']['stop'])),
		);
		$time = 'DATE_FORMAT(time, "%Y-%m%-%dT%TZ") time';

		$sTable = \Core\DB::$db->prepare(
			'('.
			'SELECT glucose_id PK, "Glucose" model, time,'.
			'value, notes FROM glucose '.$where.
			') UNION ('.
			'SELECT insulin_id PK, "Insulin", time,'.
			'CONCAT(type, ":", units) value, notes FROM insulin '.$where.
			') ORDER BY time DESC'
		);
		$sTable->execute(array_merge($pWhere, $pWhere));
		echo json_encode($sTable->fetchAll(\PDO::FETCH_OBJ));
		$sTable->closeCursor();
	}
}
