<?php namespace Core;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Model
{
	protected static $columns = [];


	public static function set($PK = '', &$incoming = '')
	{
		$set = array();
		if (empty($incoming))
			$incoming = json_decode(file_get_contents('php://input'));

		if ( ! is_object($incoming)) return $PK;
		foreach (static::$columns AS $col => $attrs) {
			if (
				property_exists($incoming, $col)
				&& empty($incoming->$col)
				&& isset($attrs['required'])
				&& $attrs['required'] === TRUE
			) http_response_code(400) && exit($col.' is required.');

			if (isset($attrs['fromSession']) && empty($PK)) {
				$set[$col] = $_SESSION[$attrs['fromSession']];
				continue;
			}

			if ( ! isset($attrs['type'])) continue;
			if ( ! isset($incoming->$col)) continue;
			switch ($attrs['type']) {
			case 'int':
				$set[$col] = (int) $incoming->$col;
				break;
			case 'float':
				$set[$col] = (float) $incoming->$col;
				break;
			case 'datetime':
				if (preg_match('/^[\d-:\. TZ]+$/', $incoming->$col))
					$set[$col] = $incoming->$col;
				break;
			case 'enum':
				if (
					! isset($attrs['options'])
					|| ! in_array($incoming->$col, $attrs['options'])
				) continue;
				$set[$col] = $incoming->$col;
			default:
				$set[$col] = \Core\XSS::clean($incoming->$col);
			}
		}

		if ( ! empty($set))
			empty($PK) ? $PK = self::insert($set) : self::update($set, $PK);
		return $PK;
	}

	private static function insert(&$set)
	{
		$PK = \Core\DB::nextPK(static::table(), static::PK());
		$SQL = 'INSERT INTO '.static::table().' ';
		$columns = [static::PK()];
		$params = [':'.static::PK() => $PK];
		foreach ($set AS $col => $val) {
			$columns[] = $col;
			$params[':'.$col] = $val;
		}
		$SQL .= '('.implode(',', $columns).') '.
			'VALUES ('.implode(',', array_keys($params)).')';
		$sInsert = \Core\DB::$db->prepare($SQL);
		if ( ! $sInsert->execute($params))
			http_response_code(500) && exit('Failed to create object :/');
		$sInsert->closeCursor();

		return $PK;
	}

	private static function update(&$set, &$PK)
	{
		$SQL = 'UPDATE '.static::table().' SET ';
		$columns = $params = array();
		foreach ($set AS $col => $val) {
			$columns[] = $col.' = :'.$col;
			$params[':'.$col] = $val;
		}
		$SQL .= implode(', ', $columns).' '.
			'WHERE '.static::PK().' = :'.static::PK();
		$params[':'.static::PK()] = $PK;
		$sUpdate = \Core\DB::$db->prepare($SQL);
		$sUpdate->execute($params);
		$sUpdate->closeCursor();
	}


	private static function name()
	{
		$classParts = explode('\\', get_called_class());
		return strtolower(end($classParts));
	}

	public static function PK()
	{
		if (isset(static::$PK) && ! empty(static::$PK))
			return static::$PK;
		return static::name().'_id';
	}

	public static function table()
	{
		if (isset(static::$table) && ! empty(static::$table))
			return static::$table;
		return static::name();
	}
}
