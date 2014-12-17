<div class="login_frame">
	<h2>Forgot password</h2>

<?php if (isset($msg)) { echo "<div class=\"alert\">$msg</div>"; } ?>
<?php if (isset($error)) { echo "<div class=\"error\">$error</div>"; } ?>
<?php if (isset($success)) { echo "<div class=\"success\">$success</div>"; } ?>

	<div id="email_form_container">
		<p>Enter your email address to be sent a link to reset your password.</p>

		<form method="post" action="<?=site_url(array('login', 'forgot_password'));?>" id="email_form">
		<div>
			<h4>Email Address:</h4>
			<input name="email_addr" type="text" value="<?=set_value('email_addr')?>" size="30" />
		</div>

		<div>
			<input type="submit" value="Continue"/>
		</div>

		</form>
	</div>
</div>