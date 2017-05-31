<?php

/*
	thread logic
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

require_once './var/logics.class.php';

class ThreadLogic extends Logics {
	protected $thread;
	protected $link;

	public function posts() {
		if (!Router::getPart(1) && !Router::getPart(2)) {
			Router::goTo();
		}

		$tid = filter_var(Router::getPart(1), FILTER_SANITIZE_NUMBER_INT);

		if (empty($tid)) {
			Router::goTo();
		}

		$thread = MYSQL::$db->query('
						select 
							tid, fid, uid, title, date, lastPostId, posts, views,
							(select title from forums as f where f.fid = t.fid limit 1) as forum
						from 
							threads as t
						where 
							tid = '.$tid.'
						limit 1
						');

		if (!$thread->num_rows) {
			Router::goTo();
		}

		$thread = $thread->fetch_assoc();

		$thread['forumLink'] = SGF\Config::$vars['urls']['directory'] . 'forum/' . $thread['fid'] . '/' . Router::partPretty($thread['forum']) . '/';

		$thread['answers'] = number_format($thread['posts'] - 1);
		$thread['views']   = number_format(++$thread['views']);

		$this->thread = $thread;

		TPL::set('thread', $thread);

		$this->link = 'thread/' . $thread['tid'] . '/' . Router::partPretty($thread['title'], 10);

		TPL::set('link', Config::$vars['urls']['directory'] . $this->link);

		MYSQL::$db->query('
				update 
					threads 
				set 
					views = views + 1
				where 
					tid = '.$tid.'
				limit 1
				');

		$limit = 10;

		$pages = ceil($thread['posts'] / $limit);

		TPL::set('all_pages', $pages);

		if (Router::getPart(3) == 'editor') {
			TPL::set('editor', true);
		}

		if (Router::getPart(3) == 'page' && Router::getPart(4)) {
			$page = filter_var(Router::getPart(4), FILTER_SANITIZE_NUMBER_INT);

			$page = max($page, 1);
			$page = min($page, $pages);

			TPL::set('current_page', $page);

			$offset = ($page - 1) * $limit;
		} else if (Router::getPart(3) == 'lastpost') {
			TPL::set('current_page', $pages);

			$offset = ($pages - 1) * $limit;

			TPL::set('scroll_to', $thread['lastPostId']);
		} else if (Router::getPart(3) == 'post' && Router::getPart(4)) {
			$pid = filter_var(Router::getPart(4), FILTER_SANITIZE_NUMBER_INT);

			$postsIds_result = MYSQL::$db->query('
										select 
											pid 
										from 
											posts 
										where 
											tid = '.$tid.'
										order by 
											date asc
										');

			$i = 0;
			$page = 1;

			while ($post = $postsIds_result->fetch_assoc()) {
				if ($i == $limit) {
					$i = 1;
					$page++; 
				} else {
					$i++;
				}

				if ($post['pid'] == $pid) {
					break;
				}
			}

			TPL::set('current_page', $page);

			$offset = ($page - 1) * $limit;

			TPL::set('scroll_to', $pid);
		} else {
			TPL::set('current_page', 1);

			$offset = 0;
		}

		$posts_result = MYSQL::$db->query('
								select 
									p.pid as pid, p.tid as tid, p.date as date, p.content as content,
									u.uid as uid, u.username as username, u.color as color, u.posts as posts
								from 
									posts as p
								inner join 
									users as u 
								on
									p.uid = u.uid 
								where 
									p.tid = '.$tid.'
								order by 
									p.date asc
								limit 
									'.$limit.'
								offset 
									'.$offset
								);

		$posts = array();

		$i = $offset;

		while ($post = $posts_result->fetch_assoc()) {
			$post['num'] = $i;

			$post['date'] = $this->time_compare($post['date']);

			$post['profile'] = SGF\Config::$vars['urls']['directory'] . 'profile/' . $post['uid'] . '/' . Router::partPretty($post['username']) . '/';

			$post['posts'] = number_format($post['posts']);

			$posts[] = $post;

			$i++;
		}

		TPL::set('posts', $posts);
	}

	public function newpost($data) {
		if (!$this->user) {
			Router::goTo(SGF\Config::$vars['urls']['directory'] . 'member/login/goback/' . $this->link . '/editor');
		}

		if (isset($data['content'])) {
			$data['content'] = $this->textFilter($data['content']);

			if (strlen($data['content']) < 3 || strlen($data['content']) > 5000) {
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
					Router::goTo(SGF\Config::$vars['urls']['directory'] . 'member/login/goback/' . $this->link . '/editor');
				}

				MYSQL::$db->query('
								insert into posts 
									(tid, uid, content) 
								values 
									('.$this->thread['tid'].', '.$user['uid'].', "'.$data['content'].'")
								');

				$lastPostId = MYSQL::$db->insert_id;

				MYSQL::$db->query('
								update 
									forums 
								set 
									lastPostId = '.$lastPostId.', 
									postsNum = postsNum + 1 
								where 
									fid = '.$this->thread['fid'].' 
								limit 1
								');

				MYSQL::$db->query('
								update 
									threads 
								set 
									lastPostId = '.$lastPostId.', 
									posts = posts + 1
								where 
									tid = '.$this->thread['tid'].' 
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

				Router::goTo(SGF\Config::$vars['urls']['directory'] . $this->link . '/lastpost');
			}
		}
	}
}
?>