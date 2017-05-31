<?php

/*
	init class
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

class Init {
	public function __construct() {
		Router::generateURI(Config::$vars['urls']['directory']);

		$action = filter_var(strtolower(trim(Router::getPart(0))), FILTER_SANITIZE_STRING);
		$method = filter_var(strtolower(trim(Router::getPart(1))), FILTER_SANITIZE_STRING);

		if (empty($action)) {
			Router::setPart(0, 'index');
			Router::setPart(1, 'default');

			$action = 'index';
			$method = 'default';
		}

		$path = Config::$vars['paths']['sites'] . '/' . $action . '.site.php';

		if (is_file($path)) {
			require $path;

			$action = 'SGF\\' . $action . 'Site';

			$site = new $action();

			if (method_exists($site, $method)) {
				$site->$method();
			} else {
				$site->default();
			}
		} else {
			Router::setPart(0, 'index');
			Router::setPart(1, 'default');

			require Config::$vars['paths']['sites'] . '/index.site.php';

			$site = new IndexSite();

			$site->default();
		}
	}
}
?>