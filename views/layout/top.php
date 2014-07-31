<!DOCTYPE html>
<head>
<title>DM.track<?php
if ( ! empty(\Core\Router::$uri)) echo ' :: ', \Core\Router::$uri; ?></title>
<meta name="viewport" content="width=device-width, user-scalable=no" />
<base href="<?= \Config::$base ?>" />
<link rel="stylesheet" href="public/css/style.css" />
</head>

<body>
<!-- This Source Code Form is subject to the terms of the Mozilla Public
	- License, v. 2.0. If a copy of the MPL was not distributed with this
	- file, You can obtain one at http://mozilla.org/MPL/2.0/. -->
<header>
<h1 id="site-title">
	<a href="">DM.track</a>
</h1>

<?php if (\Core\Session::authenticated()): ?>
<nav><ul>
<?php foreach (
	array(
		'' => 'Table',
		'graphs/glucose' => 'Graph:Glucose',
		'auth/logout' => 'Logout'
	) AS $url => $name
): ?>
	<li><a href="<?= $url ?>"><?= $name ?></a></li>
<?php endforeach; ?>
</ul></nav>
<?php endif ?>
</header>

<?php if (isset($asides)) { ?>
<aside>
<?php foreach ($asides AS $aside) \Core\View::show($aside); ?>
</aside>
<?php } ?>

<main<?= isset($asides) ? '' : ' class="no-asides"' ?>>
