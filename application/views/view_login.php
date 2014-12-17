<div class="login_frame">
	<h2>Sign-in</h2>

<?php if (isset($msg)) { echo "<div class=\"alert\">$msg</div>"; } ?>
<?php if (isset($error)) { echo "<div class=\"error\">$error</div>"; } ?>
<?php if (isset($success)) { echo "<div class=\"success\">$success</div>"; } ?>

	<div id="email_form_container">
		<p>In order to continue, please enter your email address and password.</p>

		<form method="post" action="<?=site_url(array('login'));?>" id="email_form">
		<div>
			<h4>Email Address:</h4>
			<input name="email_addr" type="text" value="<?=set_value('email_addr')?>" size="30" />
		</div>

		<div>
			<h4>Password:</h4>
			<input name="password" type="password" value="" size="30" />
		</div>

		<div>
			<input type="submit" value="Continue"/>
		</div>

		</form>

        <p>
            If you forgot your password, you can <a href="<?=site_url(array('login', 'forgot_password'))?>">reset your password</a>.
        </p>

        <p>
            If you don't have an account, <a href="<?=site_url(array('login', 'register'))?>">register a new account</a>.
        </p>
	</div>
</div>