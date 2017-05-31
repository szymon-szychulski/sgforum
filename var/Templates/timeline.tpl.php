<?php include '_header.tpl.php' ?>

	<main>
		<article id="forums" class="threads">
			<h2>Timeline</h2>

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

			<p class="toup"><a href="#forums">to the top</a></p>
		</article>
	</main>

<?php include '_footer.tpl.php' ?>