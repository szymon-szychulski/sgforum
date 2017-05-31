<?php

/*
	newthread site
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

require_once './var/sites.class.php';

class NewThreadSite extends Sites {
	public function default() {
		$newthread = $this->loadLogic('newthread');	

		$newthread->thread();

		if ($_POST) {
			$newthread->add($_POST);
		}

		TPL::render('newthread');
	}
}
?>