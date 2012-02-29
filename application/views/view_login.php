<div class="login_frame">
	<h2>Sign-in or Create New Account</h2>

<?php if (isset($msg)) { echo "<div class=\"alert\">$msg</div>"; } ?>
<?php if (isset($error)) { echo "<div class=\"error\">$error</div>"; } ?>
<?php if (isset($success)) { echo "<div class=\"success\">$success</div>"; } ?>

	<div id="email_form_container">
		<form method="post" action="<?=site_url(array('login'));?>" id="email_form">
		<input type="hidden" name="action" value="send_login_code" />
		<h3>What is your email address?</h3>
		<div>
			My Email Address is:
			<input name="email_addr" type="text" value="<?=set_value('email_addr')?>" size="30" />
		</div>
		
		<div>
			<input type="submit" value="Continue"/>
		</div>
		
		<h4>What about my password?</h4>
		<p>As an alternative to remembering your password, simply enter your email address above
		and we will send you an authorization code that logs you in to our system.</p>

		</form>
	</div>
</div>