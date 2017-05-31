<?php

/*
	index logic
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

require_once './var/logics.class.php';

class IndexLogic extends Logics {
	public function forums() {
		TPL::set('title', 'Forums');

		if ($this->user) {
			// unread posts
			// last visited
		}

		$categories_result = MYSQL::$db->query('
							select 
								cid, title
							from 
								categories
							order by 
								item asc
							');

		$categories = array();

		while ($category = $categories_result->fetch_assoc()) {
			$categories[] = $category;
		}

		TPL::set('categories', $categories);

		$forums_result = MYSQL::$db->query('
						select 
							f.fid as fid, f.cid as cid, f.title as title, f.lastPostId is not null as lastPost, f.lastPostId as lastPostId, f.threadsNum as threadsNum, f.postsNum as postsNum,
							lp.tid as lp_tid, lp.uid as lp_uid, lp.date as lp_date,
							(select username from users where uid = lp.uid limit 1) as lp_username,
							(select title from threads where tid = lp.tid limit 1) as lp_title
						from 
							forums as f
						left join 
							posts as lp 
						on 
							f.lastPostId = lp.pid 
						order by 
							f.cid asc, f.item asc
						');

		$forums = array();

		if ($forums_result->num_rows > 0) {
			while ($forum = $forums_result->fetch_assoc()) {
				$forum['link'] = SGF\Config::$vars['urls']['directory'] . 'forum/' . $forum['fid'] . '/' . Router::partPretty($forum['title']) . '/';

				$forum['unread'] = false;

				if ($forum['lastPost']) {
					$forum['lp_link'] = SGF\Config::$vars['urls']['directory'] . 'thread/' . $forum['lp_tid'] . '/' . Router::partPretty($forum['lp_title'], 10) . '/lastpost';

					$forum['lp_date'] = $this->time_compare($forum['lp_date'], false);

					// if () {
						$forum['unread'] = true;
					// }
				}

				$forum['threadsNum'] = number_format($forum['threadsNum']);
				$forum['postsNum'] = number_format($forum['postsNum']);

				$forums[$forum['cid']][] = $forum;
			}
		}

		TPL::set('forums', $forums);
	}
}
?>