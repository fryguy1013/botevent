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

<?php $this->load->view('view_event_header'); ?>


<div id="event_register_form">
<?=form_open_multipart("event/register/".$event->id)?>
<?=form_hidden('hide_registration', 'TRUE')?>

<? if (!empty($registration_errors)): ?><div class="error"><?=$registration_errors?></div><? endif; ?>

<h2>Select which people are going to attend</h2>
<div id="event_register_competitors">
<? foreach ($team_members as $person): ?>
	<div class="event_person">		
		<?=form_checkbox('person[]', $person->id, is_array($form_person) && in_array($person->id, $form_person))?>
		<div class="event_person_thumbnail">
			<?=img(!empty($person->thumbnail_url)?$person->thumbnail_url:'/images/nopicture.png')?>
		</div>
		<div class="event_person_name"><?=htmlentities($person->fullname)?></div>
		<div class="event_person_edit"><a href="<?=site_url(array('person', 'edit', $person->id))?>" id="edit_member_<?=$person->id?>">Edit</a></div>
	</div>
<? endforeach; ?>
	<div style="clear: both;" />
</div>
<p id="add_member"><a href="#">Add a person to your team</a></p>

<div class="event_register_add_person_frame">
	<? if (!empty($add_member_errors)): ?><div class="error"><?=htmlentities($add_member_errors)?></div><? endif; ?>

	<?=form_hidden('person_id', set_value('person_id', ''))?>
	<h3 id="add_member_heading">Add Person to Team</h3>
	<p>
		<div>Full Name:</div>
		<?=form_input("fullname", set_value('fullname', ''), 'size="25"')?>
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
		<?=form_dropdown('dob_month', $months, set_value('dob_month'), 'id="dob_month"')?>
		<?=form_dropdown('dob_day', $days, set_value('dob_day'), 'id="dob_day"')?>
		<?=form_dropdown('dob_year', $years, set_value('dob_year', 1984), 'id="dob_year"')?>
	</p>	
	
	<p>
		<?=form_submit('submit', !empty($show_edit_member) ? 'Edit Member' : 'Add Member', 'id="add_member_button"')?>
		<?=form_reset('submit', 'Cancel')?>
	</p>
</div>

<h2>Select the robots that will be present</h2>
<div id="event_register_entries">
<? foreach ($team_entries as $entry): ?> 
	<div class="event_entry">
		<!-- <?=htmlentities($entry->event_division)?> -->
		<?=form_checkbox('entry[]', $entry->id, is_array($form_entry) && in_array($entry->id, $form_entry))?>
		<div><?=form_dropdown("entry_division[$entry->id]", $event_divisions,
			isset($form_entry_division[$entry->id]) ? $form_entry_division[$entry->id] :
			(isset($form_entry_division_base[$entry->id]) ? $form_entry_division_base[$entry->id] :
			$entry->event_division))?></div>
		
		<div class="event_entry_thumbnail">
			<?=img(!empty($entry->thumbnail_url)?$entry->thumbnail_url:'/images/nopicture-entry.png')?>
		</div>
		<div class="event_entry_name"><?=htmlentities($entry->name)?></div>
		<div class="event_entry_edit"><a href="<?=site_url(array('entry', 'edit', $entry->id))?>" id="edit_entry_<?=$entry->id?>">Edit</a></div>
	</div>
<? endforeach; ?>
<? if (count($team_entries) == 0): ?>
	<div class="error">There are no robots. Add one first</div>
<? endif; ?>
	<div style="clear: both;"></div>
</div>

<p id="add_entry"><a href="#">Add a robot to your team</a></p>
<div class="event_register_add_entry_frame">
	<? if (!empty($add_entry_errors)): ?><div class="error"><?=htmlentities($add_entry_errors)?></div><? endif; ?>

	<?=form_hidden('entry_id', set_value('entry_id', ''))?>
	<h3 id="add_entry_heading">Add Entry to Team</h3>
	<p>
		<div>Name:</div>
		<input name="entry_name" type="text" value="<?=set_value('entry_name', '')?>" size="25" />
	</p>

	<p>
		<div>Division: </div>
		<?=form_dropdown("entry_div", $event_divisions, set_value('entry_div'))?>
	</p>

	
	<p>
		<div>Picture: (optional)</div>
		<?=form_upload('entry_photo')?>
	</p>
	
	<p>
		<?=form_submit('submit', !empty($show_edit_entry) ? 'Edit Entry' : 'Add Entry', 'id="add_entry_button"')?>
		<?=form_reset('submit', 'Cancel')?>
	</p>
</div>

<p id="register_button_frame"><?=form_submit('submit', 'Register')?></p>

<?=form_close()?>
</div>


<script type="text/javascript">

function update_member_checked() {
	$(this).parent().css('background', this.checked ? '#aaf' : '');
}

var which_visible = 'none';
function update_frames_visibility()
{
	$('p#add_member').toggle(which_visible != 'add_member');
	$('div.event_register_add_person_frame').toggle(which_visible == 'add_member');

	$('p#add_entry').toggle(which_visible != 'add_entry');
	$('div.event_register_add_entry_frame').toggle(which_visible == 'add_entry');
	$("form:not(.filter) :input[type=text]:visible:first").focus();

}
function make_update_frames_closure(x)
{
	return function()
	{
		which_visible = x;
		update_frames_visibility();
		return false;
	}
}
function make_edit_member_closure(id, name, email, dob_month, dob_day, dob_year)
{
	return function()
	{
		$('input[name=person_id]').val(id);
		$('input[name=fullname]').val(name);
		$('input[name=email_addr]').val(email);
		if (dob_month)
			$('select[name=dob_month]').find("option[value='" + dob_month + "']").attr('selected', 'selected');
		if (dob_day)			
			$('select[name=dob_day]').find("option[value='" + dob_day + "']").attr('selected', 'selected');
		if (dob_year)
			$('select[name=dob_year]').find("option[value='" + dob_year + "']").attr('selected', 'selected');
		$('#add_member_heading').text(!id ? 'Add Person To Team' : 'Edit Person');		
		$('#add_member_button').val(!id ? 'Add Member' : 'Edit Member');
		return make_update_frames_closure('add_member')();
	}
}

function make_edit_entry_closure(id, name)
{
	return function()
	{
		$('input[name=entry_id]').val(id);
		$('input[name=entry_name]').val(name);

		$('#add_entry_heading').text(!id ? 'Add Entry to Team' : 'Edit Entry');		
		$('#add_entry_button').val(!id ? 'Add Entry' : 'Edit Entry');
		return make_update_frames_closure('add_entry')();
	}
}

$(document).ready(function() {
	$('div.event_person > input[type=checkbox]')
		.change(update_member_checked)		
		.click(update_member_checked)
		.each(update_member_checked);
		
	$('div.event_entry > input[type=checkbox]')
		.change(update_member_checked)
		.click(update_member_checked)
		.each(update_member_checked);
	
	$('p#add_member > a').click(make_edit_member_closure('', '', '', '1', '1', '1984'));
	$('div.event_register_add_person_frame').find('input[type=reset]').click(make_update_frames_closure('none'));	
	$('p#add_entry > a').click(make_edit_entry_closure('', ''));
	$('div.event_register_add_entry_frame').find('input[type=reset]').click(make_update_frames_closure('none'));
	
	<? if (!empty($show_add_member)): ?>
	which_visible = 'add_member';
	<? elseif (!empty($show_add_entry)): ?>
	which_visible = 'add_entry';
	<? endif; ?>
	update_frames_visibility();


	<? if (!empty($hide_form)): ?>
	$('div#event_register_form').hide();
	
	$('div.event_registerbutton > a').click(function() {
		$('div.event_registration').hide();
		$('div#event_register_form').show();
		return false;
	});
	<? endif; ?>
	
	
	
	<? foreach ($team_members as $person): ?>
	<? $dob_parts = explode('/', $person->dob); ?>			
		$('a#edit_member_<?=$person->id?>').click(make_edit_member_closure(
			<?=json_encode($person->id)?>,
			<?=json_encode($person->fullname)?>,
			<?=json_encode($person->email)?>,
			<?=json_encode($dob_parts[0])?>,
			<?=json_encode($dob_parts[1])?>,
			<?=json_encode($dob_parts[2])?>
		));
	<? endforeach; ?>

	<? foreach ($team_entries as $entry): ?>
		$('a#edit_entry_<?=$entry->id?>').click(make_edit_entry_closure(
			<?=json_encode($entry->id)?>,
			<?=json_encode($entry->name)?>
		));
	<? endforeach; ?>
	
	
});
</script>