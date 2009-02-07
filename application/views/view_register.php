<style type="text/css">
.error {
	padding: 1em;
	border: 1px solid #ff0000;
	background: #ffaaaa;
}

.login_frame
{
	border: 1px solid #000;
	background: #eee;
	width: 36em;
	padding: 1em;
	margin-right: auto;
	margin-left: auto;
	margin-bottom: 1em;
}

h2
{
	margin-top: .25em;
}

#email_form_container
{
	border: 1px solid #000;
	background: #ddd;
	margin: 1em;
	padding: 1em;
	margin-right: auto;
	margin-left: auto;
}

#email_form_container h3
{
	margin-top: .25em;
	margin-bottom: .25em;
}

#email_form_container div
{
	margin-bottom: .75em;
}

</style>

<form method="post" action="<?=site_url('login/register');?>" id="email_form">
<input type="hidden" name="action" value="register" />
<div class="login_frame">
	<?php if (isset($error)) { echo "<div class=\"error\">$error</div>"; } ?>
	
	<h2>Finish creating account</h2>
	
	<p>
		My Full Name:
		<input name="fullname" type="text" value="<?=set_value('fullname', $openid_fullname)?>" size="25" />
	</p>
	
	<? if (empty($userurl)): ?>
		<p>Email address: <?=$email_addr?></p>
		
		<h3>Enter password:</h3> 
		<p><div>Password: </div><input name="password1" type="password" size="12" /></p>
		<p><div>Confirm Password: </div><input name="password2" type="password" size="12" /></p>
	<? else: ?>
		<p>Email Address: <?=form_input("email_addr", set_value('email_addr', $openid_email), 'size="40"')?></p>
	<? endif; ?>
	
	<p>
		<label>
			<?=form_checkbox("is_adult", 'is_adult')?>
			<span>I am at least 18 years of age</span>
		</label>
	</p>
</div>

<div class="login_frame">
	<h2>Create your team</h2>
	<p>
		<div class="event_register_heading">Team Name:</div>
		<?=form_input("team_name", set_value('team_name', ''))?>
	</p>

	<p>
		<div class="event_register_heading">Website:</div>
		<?=form_input("team_website", set_value('team_website', ''), 'size="35"')?>
	</p>
</div>

<div class="login_frame">
		<input type="submit" value="Create Account"/>
</div>
</form>

