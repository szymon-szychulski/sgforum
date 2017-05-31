<?php

/*
	session class
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

class Session  {
	public static function start() {
		session_start();
	}

	public static function regenerate() {
		session_regenerate_id();
	}

	public static function exist($var) {
		return isset($_SESSION[$var]);
	}

	public static function get($var) {
		return $_SESSION[$var];
	}

	public static function set($var, $val) {
		$_SESSION[$var] = $val; 
	}

	public static function plusOne($var) {
		$_SESSION[$var]++; 
	}

	public static function minusOne($var) {
		$_SESSION[$var]--; 
	}

	public static function remove($var) {
		unset($_SESSION[$var]);
	}

	public static function close() {
		session_unset();
		session_destroy();
	}
}
?>