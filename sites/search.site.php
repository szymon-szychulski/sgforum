<?php

/*
	search site
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

require_once './var/sites.class.php';

class SearchSite extends Sites {
	public function default() {
		$search = $this->loadLogic('search');

		$search->find();

		// TPL::render('search');
	}
}
?>