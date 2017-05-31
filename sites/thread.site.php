<?php

/*
	thread site
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

require_once './var/sites.class.php';

class ThreadSite extends Sites {
	public function default() {
		$thread = $this->loadLogic('thread');

		$thread->posts();

		if ($_POST) {
			$thread->newpost($_POST);
		}

		TPL::render('thread');
	}
}
?>