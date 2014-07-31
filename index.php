<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require 'config.php';
if (
	isset($_SERVER['REQUEST_URI'])
	&& strpos($_SERVER['REQUEST_URI'], Config::$base) !== 0
) http_response_code(404) && exit;

if ( ! isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on')
	http_response_code(500) && exit;
header('Strict-Transport-Security: max-age=86400');

spl_autoload_register();
Core\Session::start();
Core\CSRF::handle();
Core\Router::getURI();
date_default_timezone_set('UTC');
Core\DB::connect();

define('BASEPATH', __DIR__.'/');
Core\Router::controller('open');

if ( ! \Core\Session::authenticated())
	header('Location: '.Config::$base.'auth/login') || exit;

Core\Router::controller();
http_response_code(404) && exit;
