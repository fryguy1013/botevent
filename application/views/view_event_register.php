<?php
  $thumbnail_width = 80;
  $thumbnail_height = 100;
?>

<style type="text/css">
.error {
	margin-top: .5em;
	padding-left: .5em;
	border: 1px solid #ff0000;
	background: #ffaaaa;
}

.event_entry
{
	width: 125px;
	border: 1px solid #000;
	background: #ccc;
	float: left;
	margin: .5em;
	padding: .5em;
	text-align: center;
	overflow: hidden;
}


.event_entry_thumbnail img
{
	width: 125px;
}

.event_person
{
	width: 100px;
	border: 1px solid #000;
	background: #ccc;
	float: left;
	margin: .5em;
	padding: .5em;
	text-align: center;
}

.event_person_thumbnail img
{
	width: 100px;
}

.event_register_add_person_frame, .event_register_add_entry_frame
{
	margin-top: 1em;
	margin-left: .5em;
	border: 1px solid #000;
	padding-left: 1em;
	padding-right: 1em;
	width: 35em;
	background: #eee;
}

.badge_photo_guidelines
{
	font-size: .8em;
}

#add_member, #add_entry
{
	display: none;
}

</style>

<script src="/js/jquery.ajax_upload.1.0.min.js" type="text/javascript"></script>

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

<h2>Select which people are going to attend</h2>
<div id="event_register_competitors">
<? foreach ($team_members as $person): ?>
	<div class="event_person">		
		<?=form_checkbox('person[]', $person->id, $form_person)?>
		<div class="event_person_thumbnail">
			<?=img(!empty($person->thumbnail_url)?$person->thumbnail_url:'/images/nopicture.png')?>
		</div>
		<div class="event_person_name"><?=$person->fullname?></div>
		<div><a href="#">Edit</a></div>
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
		<?=form_submit('submit', 'Add Member')?>
		<?=form_reset('submit', 'Cancel')?>
	</p>
</div>

<h2>Select the entries that will be present</h2>
<div id="event_register_entries">
<? foreach ($team_entries as $entry): ?> 
	<div class="event_entry">
		<?=form_checkbox('entry[]', $entry->id, $form_entry)?>
		<div><?=form_dropdown("entry_division[$entry->id]", $event_divisions)?></div>
		
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
	<? if (!empty($add_member_errors)): ?><div class="error"><?=$add_member_errors?></div><? endif; ?>

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