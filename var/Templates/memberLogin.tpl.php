<?php include '_header.tpl.php' ?>

	<main>
		<article>
			<h2>Log in</h2>

			<?php if (!empty($error)): ?>
				<div class="error"><?php echo $error ?></div>
			<?php endif; ?>

			<?php if (SGF\Router::getPart(2) == 'activated'): ?>
				<div class="ok">You can now log in.</div>
			<?php endif; ?>

			<div class="boxes">
				<div>
					<h3>Your account:</h3>

					<div class="fields">
						<form action="<?php echo SGF\TPL::getLink() ?>" method="post">
							<label for="username">
								<span>username:</span> <input type="text" id="username" name="username" maxlength="16" <?php if (!empty($_POST['username'])): ?>value="<?php echo SGF\TPL::filterString($_POST['username']) ?>"<?php else: ?>placeholder="username"<?php endif; ?> >
							</label>						

							<label for="pass">
								<span>password:</span> <input type="password" id="pass" name="password" maxlength="32" placeholder="password">
							</label>

							<input type="submit" name="login" value="Go!">
						</form>
					</div>
				</div>
				<div>
					<h3>You don't have an account?</h3>

					<div class="signature">
						<a href="<?php echo SGF\Config::$vars['urls']['directory'] ?>member/register">Create new account</a> and join us.
					</div>
				</div>
			</div>
		</article>
	</main>

<?php include '_footer.tpl.php' ?>