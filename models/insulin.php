<?php namespace Models;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Insulin extends \Core\Model
{
	protected static $PK = 'injection_id';
	public static $columns = array(
		'user_id' => ['fromSession' => 'user_id'],
		'units' => ['type' => 'float'],
		'time' => ['type' => 'datetime', 'required' => TRUE],
		'type' => ['type' => 'enum', 'options' => ['lispro', 'glargine']],
		'notes' => ['type' => 'text'],
	);
}
