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
border: 1px solid #ff0000;
background: #ffaaaa;
}
</style>

<link rel="stylesheet" href="css/openid.css" />
<script type="text/javascript" src="/js/openid-jquery.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    openid.init('openid_identifier');
});
</script>
<form method="post" action="<?=site_url('login');?>" id="openid_form">

<input type="hidden" name="action" value="verify" />

<div class="login_frame">
	<h2>Sign-in or Create New Account</h2>

<?php if (isset($msg)) { echo "<div class=\"alert\">$msg</div>"; } ?>
<?php if (isset($error)) { echo "<div class=\"error\">$error</div>"; } ?>
<?php if (isset($success)) { echo "<div class=\"success\">$success</div>"; } ?>

	
	<div id="openid_choice">
		<p>Please click your account provider:</p>
		<div id="openid_btns"></div>
	</div>
	
	<div id="openid_input_area">
		<input id="openid_identifier" name="openid_identifier" type="text" value="http://" />
		<input id="openid_submit" type="submit" value="Sign-In"/>
	</div>
	<noscript>
	<p>OpenID is service that allows you to log-on to many different websites using a single indentity.
	Find out <a href="http://openid.net/what/">more about OpenID</a> and <a href="http://openid.net/get/">how to get an OpenID enabled account</a>.</p>
	</noscript>
</div>

</form>