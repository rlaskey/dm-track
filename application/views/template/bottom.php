<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

echo '</article>',PHP_EOL,
'<aside>',PHP_EOL;

if (isset($sidebars))
{
	foreach ($sidebars AS $sidebar) $this->load->view($sidebar);
	unset($sidebars,$sidebar);
}

echo '</aside><!-- #secondary -->',PHP_EOL,

	'<footer>',PHP_EOL,

	'<div>',
	'&copy; ',(date('Y') > 2012 ? '2012 - ' : ''),date('Y'),', ',
	'<a href="http://rlaskey.org/about">',
	'Richard Moss Laskey, III',
	'</a></div>',PHP_EOL;

if ($this->session->userdata('email')) echo '<div>current timezone: ',anchor(
	'http://www.timezoneconverter.com/cgi-bin/zoneinfo.tzc?tz='.
	date_default_timezone_get(),
		date_default_timezone_get()
	),'</div>';

echo '</footer>',PHP_EOL,
	'</body>';
