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

	'<script>var CI = {"base":"',site_url(),'"};</script>',PHP_EOL;

if (isset($js) && is_array($js)) foreach ($js AS $j) echo '<script src="',
	site_url('assets/js/'.$j.'.js?'),$burst,'"> </script>',PHP_EOL;
if (isset($jquery) && is_array($jquery))
{
	echo '<script src="',site_url('assets/js/jquery.js?'),$burst,
		'"> </script>',PHP_EOL;
	foreach ($jquery AS $j) echo '<script src="',
		site_url('assets/js/'.$j.'.js?'),$burst,'"> </script>',PHP_EOL;
}
unset($js,$jquery,$j);

echo '</body>';
