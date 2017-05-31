<?php

/*
	newthread logic
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

require_once './var/logics.class.php';

class NewThreadLogic extends Logics {
	protected $forum;

	public function thread() {
		if (!$this->user) {
			Router::goTo();
		}

		if (!Router::getPart(1)) {
			Router::goTo();
		}

		$fid = filter_var(Router::getPart(1), FILTER_SANITIZE_NUMBER_INT);

		if (empty($fid)) {
			Router::goTo();
		}

		$forum = MYSQL::$db->query('
						select 
							fid, title
						from 
							forums
						where 
							fid = '.$fid.'
						limit 1
						');

		if (!$forum->num_rows) {
			Router::goTo();
		}

		$this->forum = $forum->fetch_assoc();

		TPL::set('forum', $this->forum);
	}

	public function add($data) {
		if (isset($data['title']) && isset($data['content'])) {
			$data['title'] = ucfirst(filter_var(trim($data['title']), FILTER_SANITIZE_STRING));

			$data['content'] = $this->textFilter($data['content']);

			if (strlen($data['title']) < 5 || strlen($data['title']) > 50) {
				TPL::set('error', 'Title must contain more than 5 characters and can not be longer than 50.');
			} else if (strlen($data['content']) < 3 || strlen($data['content']) > 5000) {
				TPL::set('error', 'Content must contain more than 3 characters and can not be longer than 5000.');
			} else {
				$data['content'] = MYSQL::$db->real_escape_string($data['content']);

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
					$user = $user->fetch_assoc();
				} else {
					Router::goTo(SGF\Config::$vars['urls']['directory'] . 'member/login/goback/newthread/' . $this->forum['fid']);
				}

				MYSQL::$db->query('
								insert into threads 
									(fid, uid, title) 
								values 
									('.$this->forum['fid'].', '.$user['uid'].', "'.$data['title'].'")
								');

				$tid = MYSQL::$db->insert_id;

				MYSQL::$db->query('
								insert into posts
									(tid, uid, content) 
								values 
									('.$tid.', '.$user['uid'].', "'.$data['content'].'")
								');

				$lastPostId = MYSQL::$db->insert_id;

				MYSQL::$db->query('
								update 
									forums 
								set 
									lastPostId = '.$lastPostId.', 
									threadsNum = threadsNum + 1, 
									postsNum = postsNum + 1 
								where 
									fid = '.$this->forum['fid'].' 
								limit 1
								');

				MYSQL::$db->query('
								update 
									threads 
								set 
									lastPostId = '.$lastPostId.' 
								where 
									tid = '.$tid.' 
								limit 1
								');

				MYSQL::$db->query('
								update 
									users
								set 
									posts = posts + 1 
								where 
									uid = '.$user['uid'].' 
								limit 1
								');

				Router::goTo(SGF\Config::$vars['urls']['directory'] . 'thread/' . $tid . '/' . Router::partPretty($data['title'], 10));
			}
		}
	}
}
?>