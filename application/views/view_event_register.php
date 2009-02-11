<?php
	$thumbnail_width = 80;
	$thumbnail_height = 100;

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

<script type="text/javascript">

function update_member_checked() {
	$(this).parent().css('background', this.checked ? '#aaf' : '');
}
function toggle_add_member_frame() {
	$('p#add_member').toggle();
	$('div.event_register_add_person_frame').toggle();
	return false;
}
function toggle_add_entry_frame() {
	$('p#add_entry').toggle();
	$('div.event_register_add_entry_frame').toggle();
	return false;
}

$(document).ready(function() {
	$('div.event_person > input[type=checkbox]')
		.change(update_member_checked)
		.each(update_member_checked);
		
	$('div.event_entry > input[type=checkbox]')
		.change(update_member_checked)
		.each(update_member_checked);		
	
	$('p#add_member > a').click(toggle_add_member_frame);
	$('div.event_register_add_person_frame').find('input[type=reset]').click(toggle_add_member_frame);
	
	<? if (empty($show_add_member)): ?>
	toggle_add_member_frame();
	<? endif; ?>

	$('p#add_entry > a').click(toggle_add_entry_frame);
	$('div.event_register_add_entry_frame').find('input[type=reset]').click(toggle_add_entry_frame);
	
	<? if (empty($show_add_entry)): ?>
	toggle_add_entry_frame();
	<? endif; ?>

});
</script>

<?=form_open_multipart("event/register/".$event->id)?>

<? if (!empty($registration_errors)): ?><div class="error"><?=$registration_errors?></div><? endif; ?>

<h2>Select which people are going to attend</h2>
<div id="event_register_competitors">
<? foreach ($team_members as $person): ?>
	<div class="event_person">		
		<?=form_checkbox('person[]', $person->id, isset($form_person[$person->id]))?>
		<div class="event_person_thumbnail">
			<?=img(!empty($person->thumbnail_url)?$person->thumbnail_url:'/images/nopicture.png')?>
		</div>
		<div class="event_person_name"><?=$person->fullname?></div>
	</div>
<? endforeach; ?>
	<div style="clear: both;" />
</div>
<p id="add_member"><a href="#">Add a person to your team</a></p>

<div class="event_register_add_person_frame">
	<? if (!empty($add_member_errors)): ?><div class="error"><?=$add_member_errors?></div><? endif; ?>

	<h3>Add Person to Team</h3>
	<p>
		<div>Full Name:</div>
		<input name="fullname" type="text" value="<?=set_value('fullname', '')?>" size="25" />
	</p>
	
	<p>
		<div>Email Address:</div>
		<?=form_input("email_addr", set_value('email_addr', ''), 'size="40"')?>
	</p>
	
	<p>
		<div>Badge Photo Picture:</div>
		<?=form_upload('badge_photo')?>
		<div class="badge_photo_guidelines">Note: Follow the
		<a href="http://robogames.net/badges.php" target="_blank">badge photo guidelines</a>
		to ensure your registration will be accepted.</div>
	</p>
	
	<p>
		<div>Date of Birth</div>
		<?=form_dropdown('dob_month', $months, set_value('dob_month'))?>
		<?=form_dropdown('dob_day', $days, set_value('dob_day'))?>
		<?=form_dropdown('dob_year', $years, set_value('dob_year', 1984))?>
	</p>	
	
	<p>
		<?=form_submit('submit', 'Add Member')?>
		<?=form_reset('submit', 'Cancel')?>
	</p>
</div>

<h2>Select the entries that will be present</h2>
<div id="event_register_entries">
<? foreach ($team_entries as $entry): ?> 
	<div class="event_entry">
		<?=form_checkbox('entry[]', $entry->id, isset($form_entry[$entry->id]))?>
		<div><?=form_dropdown("entry_division[$entry->id]", $event_divisions, isset($form_entry_division[$entry->id])?$form_entry_division[$entry->id]:'')?></div>
		
		<div class="event_entry_thumbnail">
			<?=img(!empty($entry->thumbnail_url)?$entry->thumbnail_url:'/images/nopicture-entry.png')?>
		</div>
		<div class="event_entry_name"><?=$entry->name?></div>
	</div>
<? endforeach; ?>
<? if (count($team_entries) == 0): ?>
	<div class="error">There are no entries. Add one first</div>
<? endif; ?>
	<div style="clear: both;"></div>
</div>

<p id="add_entry"><a href="#">Add an entry to your team</a></p>
<div class="event_register_add_entry_frame">
	<? if (!empty($add_entry_errors)): ?><div class="error"><?=$add_entry_errors?></div><? endif; ?>

	<h3>Add Entry to Team</h3>
	<p>
		<div>Name:</div>
		<input name="entry_name" type="text" value="<?=set_value('entry_name', '')?>" size="25" />
	</p>
	
	<p>
		<div>Picture: (optional)</div>
		<?=form_upload('entry_photo')?>
	</p>
	
	<p>
		<?=form_submit('submit', 'Add Entry')?>
		<?=form_reset('submit', 'Cancel')?>
	</p>
</div>

<p id="register_button_frame"><?=form_submit('submit', 'Register')?></p>

<?=form_close()?>