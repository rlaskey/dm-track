<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

echo '</main>',PHP_EOL,
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


$scripts = array();
$script_prefix = 'body:';
if (isset($js) && is_array($js)) foreach ($js AS $j)
	$scripts[] = site_url('assets/js/'.$j.'.js?').$burst;
if (isset($private_js) && is_array($private_js)) foreach ($private_js AS $j)
	$scripts[] = site_url('js/show/'.$j.'.js?').$burst;

if ( ! empty($scripts))
{
	array_unshift(
		$scripts,
		$script_prefix.'var CI = {"base":"'.site_url().'"};'
	);
	foreach ($scripts AS $s)
	{
		if (strpos($s,$script_prefix) === 0) echo '<script>',
			'"use strict";',PHP_EOL,
			substr($s,strlen($script_prefix)),
			'</script>',PHP_EOL;
		else echo '<script src="',$s,'?',$burst,'"></script>',PHP_EOL;
	}
}

echo '</body>';
