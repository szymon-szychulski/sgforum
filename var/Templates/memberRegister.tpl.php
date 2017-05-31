<?php include '_header.tpl.php' ?>

	<main>
		<article>
			<h2>Sign up</h2>

			<?php if (!empty($errors)): ?>
				<div class="error">
					errors... (<?php echo count($errors) ?>):
					<ul>
						<?php foreach ($errors as $err): ?>
							<li><?php echo $err ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if (!empty($error)): ?>
				<div class="error"><?php echo ucfirst($error) ?>.</div>
			<?php endif; ?>

			<?php if (!empty($success)): ?>
				<div class="ok"><?php echo $success ?></div>
			<?php endif; ?>

			<div class="boxes">
				<div>
					<h3>Complete the fields...</h3>

					<div class="fields">
						<form id="regform" action="<?php echo SGF\TPL::getLink() ?>" method="post" autocomplete="off">
							<label for="username">
								<span>username: <u id="usernameInfo" title="username info">?</u></span> <input type="text" id="username" name="username" maxlength="16" <?php if (!empty($_POST['username'])): ?>value="<?php echo SGF\TPL::filterString($_POST['username']) ?>"<?php else: ?>placeholder="username"<?php endif; ?> >
							</label>

							<label for="password">
								<span>password:</span> <input type="password" id="password" name="password" maxlength="32" placeholder="password">
							</label>

							<label for="email">
								<span>e-mail:</span> <input type="text" id="email" name="email" maxlength="120" <?php if (!empty($_POST['username'])): ?>value="<?php echo SGF\TPL::filterString($_POST['email']) ?>"<?php else: ?>placeholder="e-mail address"<?php endif; ?> >
							</label>

							<input type="hidden" id="color" name="color" value="c0">

							<input type="submit" name="register" value="Join us"> <span id="loading" style="display: none;">loading</span>
						</form>
					</div>
				</div>
				<div>
					<h3>Your signature:</h3>

					<div class="signature">
						<a id="userSignature">?</a>
						<div id="chooseColor">
							<p class="color c0 activeColor"><i class="fa fa-check"></i></p>
							<p class="color c1"></p>
							<p class="color c2"></p>
							<p class="color c3"></p>
							<p class="color c4"></p>
							<p class="color c5"></p>
							<p class="color c6"></p>
							<p class="color c7"></p>
							<p class="color c8"></p>
							<p class="color c9"></p>
						</div>
					</div>
				</div>
			</div>
		</article>
	</main>

	<!-- register.js -->
	<script src="<?php echo SGF\Config::$vars['paths']['jscripts'] ?>/register.js"></script>

<?php include '_footer.tpl.php' ?>