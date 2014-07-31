<?php namespace Core;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class XSS_Test
{
	public static function assert($start = '', $desired = '')
	{
		$result = XSS::clean($start);
		if ($result !== $desired) return $result.' should instead look like '.
			$desired;
	}

	public static function assertMany(&$tests)
	{
		$problems = array();
		foreach ($tests AS $start => $desired) {
			$result = self::assert($start, $desired);
			if ( ! empty($result)) $problems[] = $result;
		}
		return $problems;
	}

	public static function included()
	{
		$tests = array(
			'%8' => '',
			'%008' => '',
			'%08' => '',
			'\x08' => '',
			"\x08" => '',
			'm&#x08' => 'm',
			'&#8' => '',
			'%6d%65' => '%6d%65',
			'<?php hai' => '&lt;?php hai',
			'?> hai' => '?&gt; hai',
			'<a src=foo data-baz=bar>' => '<a src="foo" data-baz="bar">',
			'<a onload="http://">' => '<a >',
			"<<sc\x00ript/xss ".PHP_EOL.'src=http://hack.it< >' => '<< >',
			'<img src="jaVaScrIpT:alert("hai")">' => '<img src="XSS :/alert("hai")">',
			'<img src="j&#x0061Va&#115'."\r".'c&#114;IpT:alert("hai")">' => '<img src="XSS :/alert("hai")">',
			'<a href=# onload="http://"<script>' => '<a href="#" >',
		);
		return self::assertMany($tests);
	}
}
