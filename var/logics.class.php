<?php

/*
	logics class
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

class Logics {
	protected $user = false;

	public function __construct() {
		MYSQL::get_db();

		$forums = MYSQL::$db->query('
							select 
								sum(threadsNum) as allThreads,
								sum(postsNum) as allPosts
							from 
								forums
							')->fetch_assoc();

		TPL::set('allThreads', number_format($forums['allThreads']));
		TPL::set('allPosts',   number_format($forums['allPosts']));

		$online = MYSQL::$db->query('
							select 
								count(uid)
							from 
								users
							')->fetch_assoc();
		/*
		$online = MYSQL::$db->query('
							select 
								count(uid)
							from 
								users
							where 
								online > 3600
							')->fetch_assoc();
		*/

		TPL::set('online', $online['count(uid)']);

		$now   = new \DateTime();
		$build = new \DateTime('2017-02-14');

		$days_uptime = $build->diff($now)->format('%a');

		TPL::set('uptime', number_format($days_uptime));

		if (Session::exist('_uid') && Session::exist('_token')) {
			$token = MYSQL::$db->real_escape_string(Session::get('_token'));

			$user = MYSQL::$db->query('
								select 
									uid, username 
								from 
									users 
								where 
									token = "'.$token.'" 
								limit 1
								');

			if ($user->num_rows) {
				$this->user = $user->fetch_assoc();

				TPL::set('user', $this->user);
			} else {
				Router::goTo(SGF\Config::$vars['urls']['directory'] . 'member/logout');
			}
		}
	}

	public function stringContains($string, $expressions) {
		if (is_array($expressions)) {
			foreach ($expressions as $exp) {
				if (strpos($string, $exp) !== false) {
					return $exp;
				}
			}
		} else {
			if (strpos($string, $expressions) !== false) {
				return $expressions;
			}
		}

		return false;
	}

	public function time_compare($timestamp, $hour=true) {
		$now = new \DateTime();

		// 2017-02-10 02:36:30
		$match = new \DateTime($timestamp);

		$diff = $now->diff($match);
		$diffDays = (int)$diff->format('%R%a');

		switch ($diffDays) {
			case 0:
				// 23:24 
				return $match->format('H:i');
				break;
			case -1:
				// yesterday, 23:24
				return 'yesterday, ' . $match->format('H:i');
				break;
			case -2:
				// 2 days ago, 22:16 / 2 days ago
				return '2 days ago' . ($hour ? ', ' . $match->format('H:i') : '');
				break;
			case -3:
				// 3 days ago, 21:37 / 3 days ago
				return '3 days ago' . ($hour ? ', ' . $match->format('H:i') : '');
				break;
			default:
				if ($match->format('Y') == $now->format('Y')) {
					// 12 Feb, 20:04 / 12 Feb
					return ($hour ? $match->format('j M\\, H:i') : $match->format('j M'));
				} else {
					// 12 Aug 2016, 19:59 / 12 Aug 2016
					return ($hour ? $match->format('j M Y\\, H:i') : $match->format('j M Y'));
				}
				break;
		}
	}

	public function textFilter($text) {
		$text = trim($text);

		$text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
		$text = htmlentities($text, ENT_QUOTES, 'UTF-8');

		// strong
		$text = preg_replace(
							'/\[b\](.*?)\[\/b\]/s', 
							'<strong>$1</strong>',
							$text
						);
		// italic
		$text = preg_replace(
							'/\[i\](.*?)\[\/i\]/s', 
							'<em>$1</em>',
							$text
						);

		// color
		$colors = array('white','darkgoldenrod','palegreen','cyan','seagreen','coral','orangered','plum','gold','darkmagenta','black');

		$text = preg_replace_callback(
								'/\[c\=(.*?)\](.*?)\[\/c\]/s',
								function ($matches) use ($colors) {
									$matches[1] = str_replace('&quot;', '', $matches[1]);
									$matches[1] = preg_replace('/[^a-z]/s', '', $matches[1]);

									if (!in_array($matches[1], $colors)) {
										return $matches[2];
									}

									return '<span style="color:'.$matches[1].';">'.$matches[2].'</span>';
								},
								$text
							);

		// size
		$sizes = array(10, 12, 16, 20, 24, 28);

		$text = preg_replace_callback(
								'/\[s\=(.*?)\](.*?)\[\/s\]/s',
								function ($matches) use ($sizes) {
									$matches[1] = str_replace('&quot;', '', $matches[1]);
									$matches[1] = preg_replace('/[^0-9]/s', '', $matches[1]);

									if (!in_array($matches[1], $sizes)) {
										return $matches[2];
									}

									return '<span style="font-size:'.$matches[1].'px;">'.$matches[2].'</span>';
								},
								$text
							);
		// image
		$allowedImgExt = array('jpg', 'jpeg', 'png', 'gif');

		$text = preg_replace_callback(
								'/\[img\](.*?)\[\/img\]/s', 
								function ($matches) use ($allowedImgExt) {
									$matches[1] = filter_var($matches[1], FILTER_SANITIZE_URL);

									$imgPath = parse_url($matches[1]);
									$extension = pathinfo($imgPath['path'], PATHINFO_EXTENSION);

									if (!in_array($extension, $allowedImgExt)) {
										return $matches[1];
									}

									return '<img src="'.$matches[1].'" alt="">';										
								},
								$text
							);
		// link
		$text = preg_replace_callback(
								'/\[a\=(.*?)\](.*?)\[\/a\]/s',
								function ($matches) {
									$matches[1] = str_replace('&quot;', '', $matches[1]);
									$matches[1] = filter_var($matches[1], FILTER_SANITIZE_URL);

									return '<a href="'.$matches[1].'" target="_blank" rel="nofollow">'.$matches[2].'</a>';										
								},
								$text
							);
		// quote
		$text = preg_replace_callback(
							'/\[quote\](.*?)\[\/quote\]/s', 
							function ($matches) {
								return '<blockquote>'.$matches[1].'</blockquote>';
							},
							$text
						);
		// code
		$text = preg_replace_callback(
							'/\[code\](.*?)\[\/code\]/s', 
							function ($matches) {
								return '<pre><code>'.$matches[1].'</code></pre>';
							},
							$text
						);

		$text = nl2br($text, false);

		$text = str_replace(array("\r\n", "\r", "\n"), '', $text);

		$text = str_replace(
							array('<br><br><blockquote', '<br><blockquote', '/blockquote><br><br>', '/blockquote><br>',
								  '<br><br><pre', '<br><pre', '/pre><br><br>', '/pre><br>'), 
							array('<blockquote', '<blockquote', '/blockquote>', '/blockquote>',
								  '<pre', '<pre', '/pre>', '/pre>'), 
							$text
						);

		return $text;
	}

	public function generateKey($length, $chars='') {
		if (!$chars) {
			$chars = '!@_0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}

		$count = strlen($chars);

		for ($i = 0, $result = ''; $i < $length; $i++) {
			$result .= substr($chars, rand(0, $count - 1), 1); 
		}
		
		return $result;
	}

	public function getIP() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}

	protected function loadLogic($logic) {
		$path = Config::$vars['paths']['logics'] . '/' . $logic . '.logic.php';

		if (is_file($path)) {
			require $path;

			$logic = 'SGF\\' . $logic . 'Logic';

			return new $logic();
		} else {
			exit('logic <q>' . $logic . '</q> does not exist');
		}
	}
}
?>