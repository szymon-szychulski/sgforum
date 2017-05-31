<?php

/*
	member site
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

require_once './var/sites.class.php';

class MemberSite extends Sites {
	public function default() {
		Router::setPart(1, 'default');

		Router::error_404();
	}

	public function login() {
		$member = $this->loadLogic('member');

		if ($_POST) {
			$member->login($_POST);
		}

		TPL::render('memberLogin');
	}

	public function register() {
		$member = $this->loadLogic('member');

		if ($_POST) {
			$member->register($_POST);
		}

		TPL::render('memberRegister');
	}

	public function activation() {
		$member = $this->loadLogic('member');

		$member->activation();		
	}

	public function logout() {
		$member = $this->loadLogic('member');

		$member->logout();
	}
}
?>