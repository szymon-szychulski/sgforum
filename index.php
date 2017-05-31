<?php

/*
	just forum // SGF v. 1.0 beta
*/

/*
	/
		static
			css
			gfx
			images
			fonts
			sounds
			jscripts

		var
			Libs
			Logics
			Templates

		sites


	index.php - we are here
*/

namespace SGF;
use SGF;

define('SGF', true);

require './var/config.php';

require Config::$vars['paths']['libs'] . '/Buffer.class.php';
require Config::$vars['paths']['libs'] . '/Router.class.php';
require Config::$vars['paths']['libs'] . '/Session.class.php';

Buffer::start();

Session::start();

require './var/Init.class.php';

new Init;

Buffer::end();
?>