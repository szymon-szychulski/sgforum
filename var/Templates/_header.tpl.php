<!doctype html>
<html lang="pl">
<head>
	<meta charset="utf-8">

	<title><?php echo $title ?> &mdash; <?php echo SGF\Config::$vars['names']['site'] ?></title>

	<meta name="robots" content="noindex, nofollow">

	<meta name="author" content="skeleton games">

	<base href="<?php echo SGF\Config::$vars['urls']['site'] ?>/">

	<!--[if IE]>
		<link rel="shortcut icon" href="<?php echo SGF\Config::$vars['paths']['gfx'] ?>/favicons/favicon-ie.ico">
	<![endif]-->
	<link rel="apple-touch-icon-precomposed" href="<?php echo SGF\Config::$vars['paths']['gfx'] ?>/favicons/favicon-touch.png">
	<link rel="icon" href="<?php echo SGF\Config::$vars['paths']['gfx'] ?>/favicons/favicon.png">

	<!-- tinos font -->
	<link rel="stylesheet" href="<?php echo SGF\Config::$vars['paths']['css'] ?>/tinos-font.css">

	<!-- font awesome. thanks. -->
	<link rel="stylesheet" href="<?php echo SGF\Config::$vars['paths']['css'] ?>/font-awesome.min.css">

	<!-- main style -->
	<link rel="stylesheet" href="<?php echo SGF\Config::$vars['paths']['css'] ?>/main.css?<?php echo date('dhis') ?>">

	<!-- color themes -->
	<meta name="theme-color" content="#c08e2c">
	<meta name="msapplication-navbutton-color" content="#c08e2c">
	<meta name="apple-mobile-web-app-status-bar-style" content="#c08e2c">
</head>
<body>
	<header>
		<nav id="menu">
			<ul>
				<li><a href="<?php echo SGF\Config::$vars['urls']['directory'] ?>" class="active">Forums</a></li>
				<li><a href="<?php echo SGF\Config::$vars['urls']['directory'] ?>timeline">Timeline</a></li>
				<li><a href="<?php echo SGF\Config::$vars['urls']['directory'] ?>search">Search</a></li>
				<li><a href="<?php echo SGF\Config::$vars['urls']['directory'] ?>users">Users</a></li>
				<?php if (empty($user)): ?>
				<li><a href="<?php echo SGF\Config::$vars['urls']['directory'] ?>member/login">Login</a></li>
				<li><a href="<?php echo SGF\Config::$vars['urls']['directory'] ?>member/register">Register</a></li>
				<?php else: ?>
				<li><a href="<?php echo SGF\Config::$vars['urls']['directory'] ?>profile/my">Profile</a></li>
				<li><a href="<?php echo SGF\Config::$vars['urls']['directory'] ?>member/logout">Logout</a></li>
				<?php endif; ?>
			</ul>
		</nav>

		<div id="logo">
			<a href="<?php echo SGF\Config::$vars['urls']['directory'] ?>">Skeleton Games</a>
		</div>
	</header>
