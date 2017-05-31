<?php

/*
	member logic
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

require_once './var/logics.class.php';

require_once Config::$vars['paths']['libs'] . '/Mailer.class.php';

class MemberLogic extends Logics {
	public function login($data) {
		TPL::set('title', 'Log in');

		if (isset($data['login']) && isset($data['username']) && isset($data['password'])) {
			$data['username'] = preg_replace('/[^# \w]+/u', '', trim($data['username']));
			$data['password'] = filter_var(trim($data['password']), FILTER_SANITIZE_STRING);

			if (!empty($data['username']) && !empty($data['password'])) {
				$acc = MYSQL::$db->query('
									select 
										uid, password, activeKey is not null as aK, ban is not null as banned, ban
									from 
										users
									where
										username = "'.$data['username'].'"
									limit 1
									');

				if ($acc->num_rows == 1) {
					$acc = $acc->fetch_assoc();

					if ($this->user) {
						TPL::set('error', 'You are currently logged in.');
					} else if (!password_verify($data['password'] . Config::$vars['PASSWORD_SALT'], $acc['password'])) {
						TPL::set('error', 'Wrong password.');
					} else if ($acc['aK']) {
						TPL::set('error', 'You must to activate an account to continue. Check your e-mail.');
					} else if ($acc['banned']) {
						if ($acc['ban'] > date('Y-m-d')) {
							TPL::set('error', 'This account is banned.');
						} else {
							MYSQL::$db->query('
									update
										users
									set
										ban = null
									where
										uid = '.$acc['uid'].'
									limit 1
									');
						}
					} else {
						$token = sha1($this->generateKey(8) . $acc['uid']);

						Session::set('_uid', $acc['uid']);
						Session::set('_token', $token);
						
						MYSQL::$db->query('
								update
									users 
								set 
									token = "'.$token.'"
								where 
									uid = '.$acc['uid'].'
								limit 1
								');

						$goback = false;

						if (SGF\Router::getPart(2) == 'goback') {
							$goback = str_replace(
										'/member/login/goback', 
										'', 
										SGF\Config::$vars['urls']['directory'] . implode('/', Router::getURI())
									);
						}

						Router::goTo($goback);
					}
				} else {
					TPL::set('error', 'Account with this username does not exist.');
				}
			}
		}
	}

	public function register($data) {
		TPL::set('title', 'Join us');

		if (isset($data['register']) && isset($data['username']) && isset($data['password']) && isset($data['email']) && isset($data['color'])) {
			$errors = array();

			$data['username'] = preg_replace('/[^-+# \w]+/u', '', trim($data['username']));

			$reservedUsernames = array('admin', 'administrator');

			if (strlen($data['username']) < 3 || strlen($data['username']) > 16) {
				$errors[] = 'username must be at least 3 characters and no more than 16';
			} else if ($this->stringContains($data['username'], $reservedUsernames)) {
				$errors[] = 'username contains unsafe expression';
			}

			$data['password'] = filter_var(trim($data['password']), FILTER_SANITIZE_STRING);

			if (strlen($data['password']) < 6 || strlen($data['password']) > 32) {
				$errors[] = 'password must be at least 6 characters and no more than 32';
			} else if ($data['password'] == $data['username']) {
				$errors[] = 'your password is too easy';
			}

			$data['email'] = filter_var(trim($data['email']), FILTER_SANITIZE_STRING);

			if (strlen($data['email']) < 8 || strlen($data['email']) > 120) {
				$errors[] = 'e-mail must be at least 8 characters and no more than 120';
			} else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
				$errors[] = 'e-mail is incorrect';
			}

			if (empty($data['color'])) {
				$errors[] = 'you must select a color signature';
			} else {
				$data['color'] = substr($data['color'], 1);

				$data['color'] = filter_var($data['color'], FILTER_SANITIZE_NUMBER_INT);

				if ($data['color'] < 0 || $data['color'] > 9) {
					$errors[] = 'color of signature is incorrect';
				}
			}

			if (empty($errors)) {
				$dataReserved = MYSQL::$db->query('
										select 
											uid 
										from 
											users 
										where 
												username = "'.$data['username'].'"
											or
												email = "'.$data['email'].'"
										limit 1
										');

				if ($dataReserved->num_rows == 1) {
					TPL::set('error', 'username or e-mail address is already reserved');
				} else {
					$password = password_hash($data['password'] . Config::$vars['PASSWORD_SALT'], PASSWORD_DEFAULT);

					$activeKey = $this->generateKey(6);

					MYSQL::$db->query('
							insert into users
								(username, password, email, color, activeKey)
							values 
								("'.$data['username'].'", "'.$password.'", "'.$data['email'].'", '.$data['color'].', "'.$activeKey.'")
							');

					if (MYSQL::$db->insert_id) {
						$subject = 'Account activation - ' . Config::$vars['names']['site'];

						$activationLink = Config::$vars['urls']['site'] . '/member/activation/' . urlencode($data['username']) . '/' . $activeKey;

						$body = "Hi, " . $data['username'] . "!<br><br><br>go to the link below, to activate your account:<br><br><a href='".$activationLink."'>" . $activationLink . "</a><br><br><br>Team " . Config::$vars['names']['site'] . ".<br><br>----------<br>user ip: " . $this->getIP();

						Mailer::connect();
						Mailer::simple_prepare($data['email'], $data['username'], $subject, $body);

						if (Mailer::send()) {
							unset($_POST['username']);
							unset($_POST['password']);
							unset($_POST['email']);

							TPL::set('success', 'Check your inbox (and spam folder) to activate the account.');
						} else {
							// echo Mailer::showError();
							TPL::set('error', 'something went wrong when sending email, try again');
						}
					} else {
						TPL::set('error', 'something went wrong, try again');
					}
				}
			} else {
				if (count($errors) == 1) {
					TPL::set('error', $errors[0]);

				} else {
					TPL::set('errors', $errors);
				}
			}
		}
	}

	public function activation() {
		if (Router::getPart(2) && Router::getPart(3)) {
			$username  = preg_replace('/[^# \w]+/u', '', trim(urldecode(Router::getPart(2))));
			$activeKey = filter_var(Router::getPart(3), FILTER_SANITIZE_STRING);

			if (!empty($username) && !empty($activeKey)) {
				$acc = MYSQL::$db->query('
									select 
										uid, activeKey is not null as aK, activeKey 
									from 
										users
									where
										username = "'.$username.'"
									limit 1
									');

				if ($acc->num_rows == 1) {
					$acc = $acc->fetch_assoc();

					if ($acc['aK']) {
						if ($acc['activeKey'] == $activeKey) {						
							MYSQL::$db->query('
									update
										users 
									set 
										activeKey = null
									where 
										uid = '.$acc['uid'].'
									limit 1
									');

							Router::goTo(SGF\Config::$vars['urls']['directory'] . 'member/login/activated');
						}
					}
				}
			}
		}

		Router::goTo();
	}

	public function logout() {
		if (Session::exist('_uid') || Session::exist('_token')) {
			Session::close();
		}

		Router::goTo();
	}
}
?>