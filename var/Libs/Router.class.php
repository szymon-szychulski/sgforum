<?php

/*
	router class
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

class Router  {
	protected static $URI;

	public static function generateURI($dir) {
		if (strlen($_SERVER['REQUEST_URI']) > 250) {
			exit('too many parameters ;[');
		}

		self::$URI = explode('/', str_replace($dir, '', $_SERVER['REQUEST_URI']));
	}

	public static function getURI() {
		return self::$URI;
	}

	public static function getPart($num) {
		if (isset(self::$URI[$num])) {
			return self::$URI[$num];
		} else {
			return false;
		}
	}

	public static function setPart($num, $val) {
		return self::$URI[$num] = $val;
	}

	public static function goTo($direct=false, $delay=false) {
		if (!$direct) {
			$direct = SGF\Config::$vars['urls']['directory'];
		}

		if ($delay) {
			header('Refresh:' . $delay . '; url=' . $direct);
		} else {
			header('Location: ' . $direct);
			exit;
		}
	}

	public static function partPretty($part, $maxLen=false) {
		$part = str_replace(array('ą','ć','ę','ł','ń','ś','ó'), array('a','c','e','l','n','s','o'), strtolower($part));

		$part = str_replace('--', '-', str_replace(array(' ', '/'), array('-', '-'), preg_replace('/[^#\/ \w]+/i', '', $part)));

		$part = trim($part, '-');

		if ($maxLen && substr_count($part, '-') > $maxLen) {
			$part_smash = explode('-', $part);

			$part_smash = array_slice($part_smash, 0, $maxLen);

			$part = implode('-', array_filter($part_smash));
		}

		return $part;
	}

	public static function error_404() {
		exit('<p>error 404: page not found</p>');
	}
}
?>