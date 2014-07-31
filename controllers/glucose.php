<?php namespace Controllers;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Glucose
{
	public static function handle()
	{
		$PK = isset(\Core\Router::$uriParts[1])
			? \Core\Router::$uriParts[1] : NULL;
		$PK = \Models\Glucose::set($PK);

		$sG = \Core\DB::$db->prepare(
			'SELECT * FROM '.\Models\Glucose::table().' '.
			'WHERE '.\Models\Glucose::PK().' = :PK '.
			'AND user_id = :user_id'
		);
		$sG->execute([':PK' => $PK, ':user_id' => $_SESSION['user_id']]);
		echo json_encode($sG->fetch(\PDO::FETCH_OBJ));
		$sG->closeCursor();
	}
}
