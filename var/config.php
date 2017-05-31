<?php

/*
	config file 
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

class Config {
	public static $vars = array(
		'names' => array(
			'site' => 'Skeleton Games',
			'domain' => 'skeletongames.com'
		),

		'urls' => array(
			'site' => 'http://127.0.0.1/sgforum',
			'directory' => '/sgforum/' // leave '/' if empty
		),

		'paths' => array(
			'css'      => './static/css',
			'gfx'      => './static/gfx',
			'images'   => './static/images',
			'fonts'    => './static/fonts',
			'sounds'   => './static/sounds',
			'jscripts' => './static/jscripts',

			'libs'      => './var/Libs',
			'logics'    => './var/Logics',
			'templates' => './var/Templates',

			'sites' => './sites'
		),

		'db' => array(
			'host'    => 'localhost',
			'user'    => 'root',
			'pass'    => 'ubuntu16',
			'name'    => 'forum',
			'port'    => 3306,
			'charset' => 'utf8mb4'
		),

		'mail' => array(
			'host'    => 'smtp.gmail.com',
			'user'    => 'example@gmail.com',
			'pass'    => 'password',
			'port'    => 587,
			'charset' => 'UTF-8'
		),

		'PASSWORD_SALT' => 'dXj@67_0'
	);
}

ini_set('error_reporting', 'true');
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Europe/Warsaw');
?>