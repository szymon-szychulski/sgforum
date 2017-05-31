<?php include '_header.tpl.php' ?>

	<main>
		<article>
			<div class="threadHead">
				<h1><?php echo $thread['title'] ?></h1>

				<div class="threadDetails">
					<p>answers <strong><?php echo $thread['answers'] ?></strong></p>
					<p>views <strong><?php echo $thread['views'] ?></strong></p>
					<p>forum <a href="<?php echo $thread['forumLink'] ?>"><?php echo $thread['forum'] ?></a></p>
				</div>
			</div>

			<div id="pagin1" class="pagination">
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

			<?php foreach ($posts as $post): ?>
				<div id="post<?php echo $post['pid'] ?>" class="post">
					<div class="postAuthor">
						<a href="<?php echo $post['profile'] ?>" class="postAuthorIcon c<?php echo $post['color'] ?>"><?php echo $post['username'][0] ?></a>
						<span class="postAuthorOnline" title="online"></span>
						<p class="postAuthorPostsNum">posts: <?php echo $post['posts'] ?></p>
					</div>
					<div class="postDescript">
						<p class="postDescriptAuthor"><strong><?php echo $post['username'] ?></strong> wrote: <?php if ($post['num']): ?><a href="<?php echo $link ?>/post/<?php echo $post['pid'] ?>" class="postId">#<?php echo $post['num'] ?></a><?php endif; ?><span class="postDescriptDate"><?php echo $post['date'] ?></span></p>
						<div class="postDescriptTxt"><?php echo $post['content'] ?></div>
					</div>
				</div>

				<?php if (!$post['num'] && $thread['answers']): ?>
					<div id="thread-line"></div>
				<?php endif; ?>
			<?php endforeach; ?>

			<div id="pagin2" class="pagination" style="margin-top: -18px;"></div>

			<h4>Write the answer...</h4>

			<?php if (!empty($error)): ?>
				<div class="error"><?php echo $error ?></div>
			<?php endif; ?>

			<div id="addPost">
				<div id="stayHere"></div>

				<div id="addPostAttach">
					<button id="pickPicture" title="add picture"><i class="fa fa-picture-o"></i></button>
					<button id="pickScript" title="add script"><i class="fa fa-code"></i></button>
					<button id="pickLink" title="add link"><i class="fa fa-link"></i></button>
				</div>

				<div id="addPostEditor">
					<div id="addPostFormat">
						<button id="formatStrong">strong</button>
						<button id="formatItalic">italic</button>
						<button id="formatColor">color</button>
						<button id="formatSize">size</button>
						<button id="formatQuote">quote</button>
					</div>

					<div id="chooseColor">
						<button class="color t0" data-textcolor="white"></button>
						<button class="color t1" data-textcolor="darkgoldenrod"></button>
						<button class="color t2" data-textcolor="palegreen"></button>
						<button class="color t3" data-textcolor="cyan"></button>
						<button class="color t4" data-textcolor="seagreen"></button>
						<button class="color t5" data-textcolor="coral"></button>
						<button class="color t6" data-textcolor="orangered"></button>
						<button class="color t7" data-textcolor="plum"></button>
						<button class="color t8" data-textcolor="gold"></button>
						<button class="color t9" data-textcolor="darkmagenta"></button>
						<button class="color t10" data-textcolor="black"></button>
					</div>

					<div id="chooseSize">
						<button class="size" style="font-size: 8px;" data-fontsize="10">the smallest</button>
						<button class="size" style="font-size: 12px;" data-fontsize="12">smaller</button>
						<button class="size default" style="font-size: 14px;" data-fontsize="14">normal</button>
						<button class="size" style="font-size: 16px;" data-fontsize="16">little larger</button>
						<button class="size" style="font-size: 20px;" data-fontsize="20">larger</button>
						<button class="size" style="font-size: 24px;" data-fontsize="24">much larger</button>
						<button class="size" style="font-size: 28px;" data-fontsize="28">the largest</button>
					</div>

					<div id="addPostBox">
						<textarea id="addPostTxt" rows="1" placeholder="enter the text..."></textarea>
					</div>

					<p id="postTxtChars"><span id="postTxtCharsNum">0</span>/5000</p>
				</div>
			</div>

			<button id="sendPost" data-action="post">Reply</button>
		</article>
	</main>

	<script>
	  window.addEventListener("load", function() {
		// scroll functions
		<?php if (!empty($scroll_to)): ?>
			if (location.href.indexOf("#post") == -1) {
				location.href += "#post<?php echo $scroll_to ?>";
			}

			if (location.href.indexOf("/lastpost") == -1) {
				document.getElementById("post<?php echo $scroll_to ?>").classList.add("highlight");
			}
		<?php endif; ?>

		<?php if (!empty($editor)): ?>
			if (location.href.indexOf("#addPost") == -1) {
				location.href += "#addPost";
			}		
		<?php endif; ?>
	  }, false);
	</script>

	<script>var LINK = "<?php echo SGF\TPL::getLink() ?>";</script>
	<script>var CSS_DIR = "<?php echo SGF\Config::$vars['paths']['css'] ?>";</script>

	<!-- thread.js -->
	<script src="<?php echo SGF\Config::$vars['paths']['jscripts'] ?>/thread.js"></script>

	<!-- addPost.js -->
	<script src="<?php echo SGF\Config::$vars['paths']['jscripts'] ?>/addPost.js"></script>

	<!-- highlight -->
	<script src="<?php echo SGF\Config::$vars['paths']['jscripts'] ?>/highlight.pack.js"></script>

	<script>hljs.initHighlightingOnLoad();</script>

<?php include '_footer.tpl.php' ?>