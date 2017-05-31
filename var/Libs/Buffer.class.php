<?php

/*
	buffer class
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

class Buffer {
	public static function start() {
		ob_start();
	}

	public static function clean() {
		ob_clean();
	}

	public static function end() {
		ob_end_flush();
	}
}
?>