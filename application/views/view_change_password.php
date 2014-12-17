<div class="login_frame">
	<h2>Change Password</h2>

<?php if (isset($msg)) { echo "<div class=\"alert\">$msg</div>"; } ?>
<?php if (isset($error)) { echo "<div class=\"error\">$error</div>"; } ?>
<?php if (isset($success)) { echo "<div class=\"success\">$success</div>"; } ?>

    <?php if (!isset($success)): ?>

	<div id="email_form_container">
		<form method="post" action="<?=site_url(array('login', 'change_password'));?>" id="email_form">

        <?php if (!isset($needs_password)): ?>
		<div>
			<h4>Old password:</h4>
			<input name="password_orig" type="password" value="" size="30" />
		</div>
        <?php endif; ?>

		<div>
			<h4>New password:</h4>
			<input name="password" type="password" value="" size="30" />
		</div>

		<div>
			<h4>New password (again):</h4>
			<input name="password2" type="password" value="" size="30" />
		</div>

		<div>
			<input type="submit" value="Continue"/>
		</div>

		</form>
	</div>

    <?php else: ?>

    <p>
        Continue to the <a href="<?=site_url()?>">main website</a>
    </p>

    <?php endif; ?>
</div>