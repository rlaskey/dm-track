<?php namespace Models;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class User extends \Core\Model
{
	protected static $PK = 'user_id';
	protected static $columns = array(
		'email' => array('required' => TRUE),
	);
}
