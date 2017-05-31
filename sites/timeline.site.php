<?php

/*
	timeline site
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

require_once './var/sites.class.php';

class TimelineSite extends Sites {
	public function default() {
		$timeline = $this->loadLogic('timeline');

		$timeline->threads();

		TPL::render('timeline');
	}
}
?>