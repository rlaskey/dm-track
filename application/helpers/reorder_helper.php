<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

if ( ! function_exists('find_next_pk'))
{
	function find_next_pk($table,$pk)
	{
		if (empty($table) || empty($pk)) return FALSE;

		$CI =& get_instance();
		$query = $CI->db->query(
			'SELECT a.'.$pk.'+1 AS blank FROM '.$table.' a '.
			'LEFT JOIN '.$table.' b ON a.'.$pk.'+1 = b.'.$pk.' '.
			'WHERE b.'.$pk.' IS NULL ORDER BY a.'.$pk
		);
		$next_pk = current($query->row());
		$query->free_result(); unset($query);
		if ( ! is_numeric($next_pk) || $next_pk < 1) $next_pk = 1;

		return $next_pk;
	}
}
