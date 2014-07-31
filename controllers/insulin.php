<?php namespace Controllers;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Insulin
{
	public static function handle()
	{
		$PK = isset(\Core\Router::$uriParts[1])
			? \Core\Router::$uriParts[1] : NULL;
		$PK = \Models\Insulin::set($PK);

		$sI = \Core\DB::$db->prepare(
			'SELECT * FROM '.\Models\Insulin::table().' '.
			'WHERE '.\Models\Insulin::PK().' = :PK '.
			'AND user_id = :user_id'
		);
		$sI->execute([':PK' => $PK, ':user_id' => $_SESSION['user_id']]);
		echo json_encode($sI->fetch(\PDO::FETCH_OBJ));
		$sI->closeCursor();
	}
}
