<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

function is_authorized()
{
	$CI =& get_instance();
	if (
		$CI->session->userdata('user_id') === FALSE &&
		$CI->uri->segment(1) !== 'auth'
	) redirect('auth/login');
	unset($login_page);
}
