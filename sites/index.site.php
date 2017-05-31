<?php

/*
	index site
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

require_once './var/sites.class.php';

class IndexSite extends Sites {
	public function default() {
		Router::setPart(1, 'default');

		$index = $this->loadLogic('index');

		$index->forums();

		// $index->stats();

		TPL::render('index');
	}
}
?>