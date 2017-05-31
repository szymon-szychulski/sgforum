<?php

/*
	sites class
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

require_once Config::$vars['paths']['libs'] . '/TPL.class.php';
require_once Config::$vars['paths']['libs'] . '/DB.class.php';

class Sites {
	public function loadLogic($logic) {
		$path = Config::$vars['paths']['logics'] . '/' . $logic . '.logic.php';

		if (is_file($path)) {
			require $path;

			$logic = 'SGF\\' . $logic . 'Logic';

			return new $logic();
		} else {
			exit('logic <q>' . $logic . '</q> does not exist');
		}
	}
}
?>