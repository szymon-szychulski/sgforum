<?php

/*
	DB class
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

class MYSQL {
	public static $db = false;

	public static function connect() {
		$db = new \mysqli(Config::$vars['db']['host'], Config::$vars['db']['user'], Config::$vars['db']['pass'], Config::$vars['db']['name'], Config::$vars['db']['port']);

		if ($db->connect_error) {
			SGF\Buffer::clean();

			echo '<p>database connection failed</p>';

			// echo '<p>error: ' . $db->connect_error . '</p>';

			exit;
		}

		$db->set_charset(Config::$vars['db']['charset']);

		self::$db = $db;
	}

	public static function get_db() {
		if (!self::state()) {
			self::connect();
		}
	}

	public static function state() {
		return self::$db;
	}

	public static function showError() {
		return '<p>error: ' . self::$db->error . '</p>';
	}

	public static function disconnect() {
		if (self::state()) {
			self::$db->close();
			self::$db = false;
		}
	}
}
?>