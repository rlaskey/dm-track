<!DOCTYPE html>
<head>
<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */
function show_title(&$title)
{
	if (isset($title) && ! empty($title)) echo $title;
	else echo 'DM.track';
}
$burst = date('Y-m-d');// update scripts/css once a day
echo '<title>',show_title($title),'</title>',PHP_EOL,
	'<meta name="viewport" content="width=device-width, user-scalable=no" />',
	PHP_EOL,
	'<link rel="stylesheet" href="',site_url('assets/css/dm.css?'),
	$burst,'" />',PHP_EOL;

if (isset($jquery) && is_array($jquery))
{
	echo '<script>var CI = {"base":"',site_url(),'"};</script>',PHP_EOL,
		'<script src="',site_url('assets/js/jquery.js?'),$burst,
		'"> </script>',PHP_EOL;
	foreach ($jquery AS $j) echo '<script src="',
		site_url('assets/js/'.$j.'.js?'),$burst,'"> </script>',PHP_EOL;
	unset($jquery,$j);
}

// load Persona for login/logout pages
if ($this->uri->segment(1) === 'auth') echo '<script src="',
	'https://login.persona.org/include.js"></script>',PHP_EOL;

echo '</head><body>',PHP_EOL,

	'<header>',PHP_EOL,
	'<h1 id="site-title"><a href="',site_url(),'">',
	show_title($title),'</a></h1>',PHP_EOL;

if ($this->session->userdata('email'))
{
	$navigation_links = array(
		'charts' => 'Charts',
		'graphs/glucose' => 'Graph:Glucose',
	);
	echo '<nav><ul>',PHP_EOL;
	foreach ($navigation_links AS $url => $name) echo '<li>',
		anchor($url,$name),'</li>',PHP_EOL;
	unset($url,$name,$navigation_links);
	echo '<li>',anchor('auth/logout','Logout'),'</li>',PHP_EOL;
	echo '</ul></nav>',PHP_EOL;
}

echo '</header>',PHP_EOL,

	'<article>',PHP_EOL;
