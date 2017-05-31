<?php

/*
	forum logic
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

require_once './var/logics.class.php';

class ForumLogic extends Logics {
	public function threads() {
		if (!Router::getPart(1) && !Router::getPart(2)) {
			Router::goTo();
		}

		$fid = filter_var(Router::getPart(1), FILTER_SANITIZE_NUMBER_INT);

		if (empty($fid)) {
			Router::goTo();
		}

		$forum = MYSQL::$db->query('
						select 
							fid, title, threadsNum, postsNum
						from 
							forums
						where 
							fid = '.$fid.'
						limit 1
						');

		if (!$forum->num_rows) {
			Router::goTo();
		}

		$forum = $forum->fetch_assoc();

		TPL::set('title', $forum['title']);

		TPL::set('forum', $forum);

		$link = 'forum/' . $forum['fid'] . '/' . Router::partPretty($forum['title'], 10);

		TPL::set('link', Config::$vars['urls']['directory'] . $link);

		if ($forum['threadsNum'] > 0) {
			$limit = 10;

			$pages = ceil($forum['threadsNum'] / $limit);

			TPL::set('all_pages', $pages);

			if (Router::getPart(3) == 'page' && Router::getPart(4)) {
				$page = filter_var(Router::getPart(4), FILTER_SANITIZE_NUMBER_INT);

				$page = max($page, 1);
				$page = min($page, $pages);

				TPL::set('current_page', $page);

				$offset = ($page - 1) * $limit;
			} else {
				TPL::set('current_page', 1);

				$offset = 0;
			}

			$threads_result = MYSQL::$db->query('
					select 
						t.tid as tid, t.uid as tuid, t.title as title, t.lastPostId as lastPostId, t.posts as posts, t.views as views,
						(select username from users where uid = t.uid limit 1) as username,
						lp.uid as lp_uid, lp.date as lp_date,
						(select username from users where uid = lp.uid limit 1) as lp_username
					from 
						threads as t
					inner join 
						posts as lp 
					on 
						t.lastPostId = lp.pid
					where 
						t.fid = '.$fid.'
					order by 
						t.lastPostId desc
					limit 
						'.$limit.'
					offset 
						'.$offset
					);

			$threads = array();

			while ($thread = $threads_result->fetch_assoc()) {
				$thread['link'] = SGF\Config::$vars['urls']['directory'] . 'thread/' . $thread['tid'] . '/' . Router::partPretty($thread['title'], 10) . '/';

				$thread['user_link'] = SGF\Config::$vars['urls']['directory'] . 'user/' . $thread['tuid'] . '/' . Router::partPretty($thread['username']) . '/';

				$thread['posts']--;
				$thread['views'] = number_format($thread['views']);

				$thread['lp_link'] = SGF\Config::$vars['urls']['directory'] . 'thread/' . $thread['tid'] . '/' . Router::partPretty($thread['title'], 10) . '/lastpost';

				$thread['lp_date'] = $this->time_compare($thread['lp_date'], false);

				$threads[] = $thread;
			}

			TPL::set('threads', $threads);
		} else {
			TPL::set('nothreads', true);
		}
	}
}
?>