<?php

/*
	forum site
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

require_once './var/sites.class.php';

class ForumSite extends Sites {
	public function default() {
		$forum = $this->loadLogic('forum');

		$forum->threads();

		TPL::render('forum');
	}
}
?>