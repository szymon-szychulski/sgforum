	<div class="stats">
		<div class="stats-center">
			<h1>Stats</h1>

			<p><i class="fa fa-cube fa-fw"></i> threads: <?php echo $allThreads ?></p>
			<p><i class="fa fa-cubes fa-fw"></i> posts: <?php echo $allPosts ?></p>
			<p><i class="fa fa-user-o fa-fw"></i> online: <?php echo $online ?></p>
			<p><i class="fa fa-clock-o fa-fw"></i> uptime: <?php echo $uptime ?> days</p>
		</div>
	</div>

	<footer>
		<nav class="big-menu">
			<div class="big-menu-sub">
				<h1>Forums</h1>

				<ul>
					<li><a href="index.html">All forums</a></li>
					<li><a href="#">Search forums</a></li>
				</ul>
			</div>
			<div class="big-menu-sub">
				<h1>Threads</h1>

				<ul>
					<li><a href="#">New thread</a></li>
					<li><a href="#">Unread threads</a></li>
					<li><a href="#">All threads</a></li>
					<li><a href="#">Search threads</a></li>
				</ul>
			</div>
			<div class="big-menu-sub">
				<h1>Posts</h1>

				<ul>
					<li><a href="#">New post</a></li>
					<li><a href="#">Unread posts</a></li>
					<li><a href="#">All posts</a></li>
					<li><a href="#">Search posts</a></li>
				</ul>
			</div>
			<div class="big-menu-sub">
				<h1>Users</h1>

				<ul>
					<li><a href="#">New user</a></li>
					<li><a href="#">All users</a></li>
					<li><a href="#">Search users</a></li>
				</ul>
			</div>

			<div class="big-menu-sub">
				<h1>Menu</h1>

				<ul>
					<li><a href="timeline.html">Timeline</a></li>
					<li><a href="#">Search</a></li>
					<li><a href="#">Users</a></li>
					<li><a href="#">Login</a></li>
					<li><a href="#">Register</a></li>
				</ul>
			</div>
		</nav>

		<div class="copyrights">
			<p>Copyright &copy; <?php echo date('Y') ?> <?php echo SGF\Config::$vars['names']['site'] ?> - all rights reserved</p>
		</div>
	</footer>

	<script>
	  window.addEventListener("load", function() {
		document.getElementById("logo").addEventListener("mouseover", function() {
			document.body.classList.add("bgAlpha");
		});

		document.getElementById("logo").addEventListener("mouseout", function() {
			document.body.classList.remove("bgAlpha");
		});
	  }, false);
	</script>
</body>
</html>