<?php namespace Core;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class DB
{
	public static $db;

	public static function connect()
	{
		try {
			self::$db = new \PDO(
				'mysql:'.
				'host='.\Config::$db['host'].';'.
				'dbname='.\Config::$db['dbname'],
				\Config::$db['username'],
				\Config::$db['password'],
				[\PDO::ATTR_PERSISTENT => \Config::$db['persistent']]
			);
		} catch (\PDOException $e) {
			http_response_code(500);
			exit('DB connection error: '.$e->getMessage());
		}
		self::$db->query('SET time_zone = "+00:00"');
	}

	public static function nextPK($table, $PK)
	{
		$pattern = '/[a-z_]+/';
		if (
			! preg_match($pattern, $table) || ! preg_match($pattern, $PK)
		) return FALSE;

		$query = self::$db->query(
			'SELECT a.'.$PK.'+1 AS blank FROM '.$table.' a '.
			'LEFT JOIN '.$table.' b ON a.'.$PK.'+1 = b.'.$PK.' '.
			'WHERE b.'.$PK.' IS NULL ORDER BY a.'.$PK
		);
		$result = $query->fetch(\PDO::FETCH_NUM);
		$query->closeCursor();
		if ($result === FALSE) return 1;
		return current($result);
	}
}
