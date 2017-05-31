<?php include '_header.tpl.php' ?>

	<main>
		<div id="brdcrumbs">
			SG &raquo; <a href="<?php echo SGF\TPL::getLink() ?>">Forums</a>
		</div>

		<?php if (!empty($user)): ?>
		<div style="float: right; display: inline-block;">
			Hi, <?php echo $user['username'] ?>! <strong><u>0 new posts</u></strong> &vert; last visit: yesterday, 21:37
		</div>
		<?php endif; ?>

		<?php foreach ($categories as $category): ?>
			<article id="category<?php echo $category['cid'] ?>">
				<h1><?php echo $category['title'] ?></h1>

				<div class="legend">
					<p class="state">&ndash;</p><p class="title">title</p><p class="threads">threads</p><p class="posts">posts</p>
				</div>

				<?php foreach ($forums[$category['cid']] as $forum): ?>
					<div class="forum">
						<p class="state"><i class="fa fa-paper-plane<?php if (!$forum['unread']): ?>-o<?php endif; ?> fa-fw"></i></p>
						<p class="title">
							<a href="<?php echo $forum['link'] ?>" class="forumTitle"><?php echo $forum['title'] ?></a> <?php if ($forum['unread']): ?><span class="unreadPosts">unread posts</span><?php endif; ?>
							<?php if ($forum['lastPost']): ?>
								<a href="<?php echo $forum['lp_link'] ?>" class="lastPost">
									<span class="lastPostThread"><?php echo $forum['lp_title'] ?></span>
									<span class="lastPostDetails"><?php echo $forum['lp_date'] ?> by <em><?php echo $forum['lp_username'] ?></em></span>
								</a>
							<?php endif; ?>
						</p>
						<p class="threads"><?php echo $forum['threadsNum'] ?></p>
						<p class="posts"><?php echo $forum['postsNum'] ?></p>
					</div>
				<?php endforeach; ?>

				<p class="toup"><a href="#category<?php echo $category['cid'] ?>">to the top</a></p>
			</article>
		<?php endforeach; ?>
	</main>

<?php include '_footer.tpl.php' ?>