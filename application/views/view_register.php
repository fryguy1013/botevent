<?
	$months = array(
		1 => "January",
		2 => "February",
		3 => "March",
		4 => "April",
		5 => "May",
		6 => "June",
		7 => "July",
		8 => "August",
		9 => "September",
		10 => "October",
		11 => "November",
		12 => "December"
	);
	$days = array();
	for ($i=1; $i<=31; $i++) $days[$i] = $i;
	$years = array();
	for ($i=2009; $i>=1920; $i--) $years[$i] = $i;
?>

<?=form_open_multipart("login/register")?>
<input type="hidden" name="action" value="register" />
<div class="login_frame">
	<?php if (isset($error)) { echo "<div class=\"error\">$error</div>"; } ?>
	
	<h2>Finish creating account</h2>
	
	<div>
		<h4>My Full Name:</h4>
		<input name="fullname" type="text" value="<?=set_value('fullname', '')?>" size="25" />
	</div>
	
	<div>
		<h4>Email address:</h4>
		<input name="email_addr" type="text" value="<?=set_value('email_addr', '')?>" size="25" />
	</div>


	<div>
		<h4>Password:</h4>
		<input name="password" type="password" value="" size="30" />
	</div>

	<div>
		<h4>Password (again):</h4>
		<input name="password2" type="password" value="" size="30" />
	</div>

	<div>
		<h4>Date of Birth</h4>
		<?=form_dropdown('dob_month', $months, set_value('dob_month'))?>
		<?=form_dropdown('dob_day', $days, set_value('dob_day'))?>
		<?=form_dropdown('dob_year', $years, set_value('dob_year', 1984))?>
	</div>
	    
	<div>
		<h4>Phone Number:</h4>
		<?=form_input("phonenum", set_value('phonenum', ''), 'size="30"')?>
	</div>

	<div>
		<h4>Badge Photo Picture:</h4>
		<?=form_upload('badge_photo')?>
		<div class="badge_photo_guidelines">Note: Follow the
		<a href="http://robogames.net/badges.php" target="_blank">badge photo guidelines</a>
		to ensure your registration will be accepted.</div>
	</div>	
</div>

<div class="login_frame">
	<h2>Create your team</h2>
	<p>
		<div class="event_register_heading">Team Name:</div>
		<?=form_input("team_name", set_value('team_name', ''))?>
	</p>

	<p>
		<div class="event_register_heading">Website: (optional)</div>
		<?=form_input("team_website", set_value('team_website', ''), 'size="35"')?>
	</p>
	
	<p>
		<div class="event_register_heading">Contact Address:</div>
		<div><?=form_input("team_addr1", set_value('team_addr1', ''), 'size="30"')?></div>
		<div><?=form_input("team_addr2", set_value('team_addr2', ''), 'size="30"')?></div>
	</p>
	
	<p>
		<div class="event_register_heading">City:</div>
		<?=form_input("team_city", set_value('team_city', ''), 'size="15"')?>
	</p>
	
	<p>
		<div class="event_register_heading">State/Province:</div>
		<?=form_input("team_state", set_value('team_state', ''), 'size="4"')?>
	</p>
	
	<p>
		<div class="event_register_heading">Post Code:</div>
		<?=form_input("team_zip", set_value('team_zip', ''), 'size="15"')?>
	</p>
	
	<p>
		<div class="event_register_heading">Nationality:</div>
		<?=form_input("team_country", set_value('team_country', ''), 'size="20"')?>
	</p>

</div>

<div class="login_frame">
	<input type="submit" value="Create Account"/>
</div>
</form>

