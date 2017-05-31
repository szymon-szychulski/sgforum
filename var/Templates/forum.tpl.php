<?php include '_header.tpl.php' ?>

	<main>
		<article id="forums" class="threads">
			<h2><?php echo $forum['title'] ?></h2>

			<div class="forumOptions">
				<p class="newThread"><a href="<?php echo SGF\Config::$vars['urls']['directory'] ?><?php if (empty($user)): ?>member/login/goback/<?php endif; ?>newthread/<?php echo $forum['fid'] ?>">New thread</a></p>
				<div class="search">
					<form action="<?php echo SGF\Config::$vars['urls']['directory'] ?>search" method="post">
						<input type="text" name="search" placeholder="enter a phrase">
						<input type="submit" value="Find">
					</form>
				</div>
			</div>

			<?php if (isset($nothreads)): ?>
				<p class="forumempty">this forum is empty</p>
			<?php else: ?>
				<div class="legend">
					<p class="state2">&ndash;</p>
					<p class="author">author</p>
					<p class="thread">thread</p>
					<p class="answers">answers</p>
					<p class="views">views</p>
				</div>

				<?php foreach ($threads as $thread): ?>
					<div class="forum">
						<p class="state2"><i class="fa fa-paper-plane-o fa-fw"></i></p>
						<p class="author"><a href="<?php echo $thread['user_link'] ?>"><?php echo $thread['username'] ?></a></p>
						<p class="thread"><a href="<?php echo $thread['link'] ?>" class="threadTitle"><?php echo $thread['title'] ?></a><span class="lastPost">(last post: <?php if ($thread['posts']): ?><a href="<?php echo $thread['lp_link'] ?>"><?php echo $thread['lp_username'] ?> - <?php echo $thread['lp_date'] ?></a><?php else: ?>&mdash;<?php endif; ?>)</span></p>
						<p class="answers"><span<?php if (!$thread['posts']): ?> class="empty"<?php endif; ?>><?php echo $thread['posts'] ?></span></p>
						<p class="views"><?php echo $thread['views'] ?></p>
					</div>
				<?php endforeach; ?>

				<p class="toup"><a href="<?php echo SGF\TPL::getLink() ?>#forums">to the top</a></p>

				<div class="pagination" style="margin: 12px 2px;">
					<?php if ($all_pages > 1): ?>
						<?php if ($current_page == 1): ?>
							<a href="javascript: return false;" title="First page" class="dis">&laquo; First</a> 
							<a href="javascript: return false;" title="Previous page" class="dis">&laquo;</a> 
						<?php else: ?>
							<a href="<?php echo $link ?>/page/1" title="First page">&laquo; First</a> 
							<a href="<?php echo $link ?>/page/<?php echo ($current_page-1) ?>" title="Previous page">&laquo;</a> 
						<?php endif; ?>

						<a>current page: <?php echo $current_page ?>/<?php echo $all_pages ?></a> 

						<?php if ($current_page == $all_pages): ?>
							<a href="javascript: return false;" title="Next page" class="dis">&raquo;</a>
							<a href="javascript: return false;" title="Last page" class="dis">Last &raquo;</a>
						<?php else: ?>
							<a href="<?php echo $link ?>/page/<?php echo ($current_page+1) ?>" title="Next page">&raquo;</a>
							<a href="<?php echo $link ?>/page/<?php echo $all_pages ?>" title="Last page">Last &raquo;</a>
						<?php endif; ?> 
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</article>
	</main>

<?php include '_footer.tpl.php' ?>