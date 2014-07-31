<?php namespace Models;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Glucose extends \Core\Model
{
	protected static $PK = 'level_id';
	public static $columns = array(
		'user_id' => ['fromSession' => 'user_id'],
		'value' => ['type' => 'int'],
		'time' => ['type' => 'datetime', 'required' => TRUE],
		'notes' => ['type' => 'text'],
	);
}
