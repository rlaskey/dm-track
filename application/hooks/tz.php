<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

function handle_tz()
{
	$CI =& get_instance();
	$tz = $CI->config->item('default_tz');
	/* expand to use defaults; use CI timezone_menu, session, etc. */
	if ( ! empty($tz)) date_default_timezone_set($tz);
}
