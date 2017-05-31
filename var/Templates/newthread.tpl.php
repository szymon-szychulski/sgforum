<?php include '_header.tpl.php' ?>

	<main>
		<article id="newthread">
			<h4>New thread / in <?php echo $forum['title'] ?></h4>

			<?php if (!empty($error)): ?>
				<div class="error"><?php echo $error ?></div>
			<?php endif; ?>

			<label for="title">
				thread title: <input type="text" id="title" maxlength="50">
			</label>

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

			<button id="sendPost" data-action="thread">Add thread</button>
		</article>
	</main>

	<script>var LINK = "<?php echo SGF\Config::$vars['urls']['directory'] ?>newthread/<?php echo $forum['fid'] ?>";</script>

	<!-- addPost.js -->
	<script src="<?php echo SGF\Config::$vars['paths']['jscripts'] ?>/addPost.js"></script>

<?php include '_footer.tpl.php' ?>