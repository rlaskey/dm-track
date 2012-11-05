<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

if ( ! function_exists('utc'))
{
	function utc($string = '',$direction = 'to')
	{
		$gmt_offset = (int) (date('O') * 60 * 60 / 100);
		if ($direction === 'from') $gmt_offset = -1 * $gmt_offset;
		$time = strtotime($string) - $gmt_offset;
		return date('y-m-d H:i',$time);
	}
}
