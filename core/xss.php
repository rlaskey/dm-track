<?php namespace Core;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class XSS
{
	private static $nonPrinting = array();
	private static $nonPrintingChar = array();

	public static function setNonPrinting()
	{
		if ( ! empty(self::$nonPrinting)) return;
		self::$nonPrinting = array_merge(
			range(0, 8), [11, 12], range(14, 31), range(127, 159),
			// UTF-16 range(55296, 57343),
			[65534, 65535]
		);
		foreach (self::$nonPrinting AS $char)
			self::$nonPrintingChar[] = hex2bin(sprintf('%02x', $char));
	}


	public static function clean(&$string)
	{
		self::cleanNonPrinting($string);
		$string = str_replace(['<?', '?>'], ['&lt;?', '?&gt;'], $string);
		$string = str_replace(
			array(
				"\t",
				'document.cookie', 'document.write', 'window.location',
				'.parentNode', '.innerHTML',
			),
			' ',
			$string
		);
		$string = preg_replace_callback(
			'#(</?)((\w+)[^<>]*)>?#i',
			'self::handleTags',
			$string
		);
		// super old browsers: care about &{, and expression:
		$string = preg_replace('#(javascript:)#i', 'XSS :/', $string);
		return $string;
	}


	private static function cleanNonPrinting(&$string)
	{
		$string = preg_replace_callback(
			'!(%|\\\x|&#x)([\da-f]+)!i',
			'self::replaceNonPrintingHex',
			$string
		);
		$string = preg_replace_callback(
			'!&#(\d+)!',
			'self::replaceNonPrintingDec',
			$string
		);
		$string = str_replace(self::$nonPrintingChar, '', $string);
	}

	private static function replaceNonPrintingHex(&$matches)
	{
		$int = hexdec($matches[2]);
		if (in_array($int, self::$nonPrinting)) return '';
		return $matches[0];
	}

	private static function replaceNonPrintingDec(&$matches)
	{
		$int = (int) $matches[1];
		if (in_array($int, self::$nonPrinting)) return '';
		return $matches[0];
	}

	private static $blockedTags = '#^(base|body|head|html|link|meta|script|xml|xss)#i';

	private static function handleTags(&$matches)
	{
		if (preg_match(self::$blockedTags, $matches[3])) return '';
		$attributes = preg_replace_callback(
			'#(\s+[\w-]+)=([^\s>\'\"]+)#i',
			'self::quoteAttributes',
			$matches[2]
		);
		$attributes = preg_replace_callback(
			'#([\w-]+)=([\'\"])([^\2]+?)\2#i',
			'self::handleAttributes',
			$attributes
		);
		return $matches[1].$attributes.'>';
	}

	private static function quoteAttributes(&$matches)
	{
		return $matches[1].'="'.$matches[2].'"';
	}

	private static $blockedAttributes = '#^(on\w|srcdoc)#i';

	private static function handleAttributes(&$matches)
	{
		if (preg_match(self::$blockedAttributes, $matches[1])) return '';
		$value = html_entity_decode(str_replace(
			["\r", "\n", "\t"], '', $matches[3]
		));
		self::cleanAttributeValue($value);
		return $matches[1].'="'.$value.'"';
	}

	private static function cleanAttributeValue(&$value)
	{
		if (strpos($value, '&') === FALSE) return;

		$value = html_entity_decode($value);
		$value = preg_replace_callback(
			'/&#x([\da-f]{1,6})/i',
			'self::chrFirstFromHex',
			$value
		);
		$value = preg_replace_callback(
			'/&#(\d+)/',
			'self::chrFirstFromDec',
			$value
		);
		self::cleanNonPrinting($value);
	}

	private static function chrFirstFromHex(&$matches)
	{
		return chr(hexdec($matches[1]));
	}

	private static function chrFirstFromDec(&$matches)
	{
		return chr($matches[1]);
	}
}

XSS::setNonPrinting();// run once
