<?php

/*
	timeline logic
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

require_once './var/logics.class.php';

class TimelineLogic extends Logics {
	public function threads() {
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
				order by 
					t.lastPostId desc
				limit 
					0, 10
				');

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
	}
}
?>