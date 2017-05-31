<?php

/*
	router class
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

class TPL {
	protected static $link;
	protected static $vars = array();

	public static function render($tpl) {
		$path = Config::$vars['paths']['templates'] . '/' . $tpl . '.tpl.php';

		if (is_file($path)) {
			extract(self::$vars);

			require $path;
		} else {
			exit('template <q>' . $tpl . '</q> does not exist');
		}
	}

	public static function set($var, $val) {
		self::$vars[$var] = $val;
	}

	public static function getLink() {
		if (self::$link) {
			return self::$link;
		} else {
			return self::$link = Config::$vars['urls']['directory'] . implode(Router::getURI(), '/');
		}
	}

	public static function filterString($string) {
		return filter_var(trim($string), FILTER_SANITIZE_STRING);
	}

	public static function filterInt($int) {
		return filter_var($int, FILTER_SANITIZE_NUMBER_INT);
	}

	public static function filterFloat($float) {
		return filter_var($float, FILTER_SANITIZE_NUMBER_FLOAT);
	}
}
?>