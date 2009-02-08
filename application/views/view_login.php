<style type="text/css">
.alert {
border: 1px solid #e7dc2b;
background: #fff888;
}
.success {
border: 1px solid #669966;
background: #88ff88;
}
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

<link rel="stylesheet" href="css/openid.css" />
<script type="text/javascript" src="js/jquery.openid.min.js"></script>
<script type="text/javascript">
$(function() {
  $('#openid').openid({
    img_path: '{{ MEDIA_URL }}img/openid/',
    txt: {
      label: 'Enter your {username} for <b>{provider}</b>',
      username: 'Username',
      title: 'Please select your account provider',
      sign: 'Sign-In'
    }
  });
});
</script>

<div class="login_frame">
	<h2>Sign-in or Create New Account</h2>

<?php if (isset($msg)) { echo "<div class=\"alert\">$msg</div>"; } ?>
<?php if (isset($error)) { echo "<div class=\"error\">$error</div>"; } ?>
<?php if (isset($success)) { echo "<div class=\"success\">$success</div>"; } ?>

<? if (empty($show_email_only)): ?>

	<form method="POST" action="<?=site_url('login');?>" id="openid"></form>
	
	<h4>What is this?</h4>
	<p>Instead of creating yet another username and password combination, you
	may login using your Google, Yahoo, AOL, or other OpenID account. If you
	don't have one of these accounts, you can create an OpenID for free at
	<a href="https://www.myopenid.com/signup">myOpenID.com</a>, or use the
	traditional email and password combination, below.</p>
	
	<h4>Do I need to give you my password?</h4>
	<p><strong>No.</strong> After selecting your provider, you will be taken to
	the provider's website, where you can enter your password. Then may be asked
	to authorize basic information to be transfered to this site. <strong>We
	will never have access to your passwords or your accounts.</strong></p>
	
	<h4>What if I don't want to use OpenID?</h4>
	<p>If you really don't want to use OpenID, then you can
	<a id="use_email_password">login</a> with an email and password.</p>
	
	<script type="text/javascript">
		$(document).ready(function() {
			$('a#use_email_password')
				.attr('href', '#')
				.click(function() {
					$('div#email_form_container').toggle();
				});
			
			$('div#email_form_container').hide();
		});
	</script>

	
<? endif; ?>
	<div id="email_form_container">
		<form method="post" action="<?=site_url('login');?>" id="email_form">
		<input type="hidden" name="action" value="email_password" />
		<h3>What is your email address?</h3>
		<div>
			My Email Address is:
			<input name="email_addr" type="text" value="<?=set_value('email_addr')?>" size="30" />
		</div>
		
		<h3>Do you have a password?</h3> 
		<div>
			<div>
			<input name="login_type" type="radio" value="new_member" <?=set_radio('login_type', 'new_member', TRUE)?> />
			No, this is my first time here.
			</div>
			<div>
			<input name="login_type" type="radio" value="returning" <?=set_radio('login_type', 'returning')?> />
			Yes, and my password is:
			<input name="email_password" type="password" size="12" />
			</div> 
		</div>
		
		<div>
			<input type="submit" value="Sign-In"/>
		</div>
		</form>
	</div>	
</div>

