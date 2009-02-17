<div class="event_registration">
	<div class="event_registration_status">
		Status: <?=$registration->status?>
	</div>
	<div class="event_registration_status_description">
		<? if ($registration->status == "new"): ?>
		Your registration is pending. The event organizer will review it, and
		accept or reject it. You will get an email when this happens, and at
		that point, you will be able to pay for your entry. 
		<? endif; ?>
	</div>

	<div class="event_registerbutton">
		<a href="<?=site_url(array('event', 'register', $event->id, 'update'))?>">Change Registration</a>
	</div>
	<div class="event_withdrawbutton">
		<a href="<?=site_url(array('event_registration', 'withdraw', $registration->id))?>">Withdraw</a>
	</div>


	<? foreach ($people as $person): ?> 
		<div class="event_person">
			<a href="<?=site_url(array('person', 'view', $person->id))?>">
			<div class="event_person_thumbnail">
				<?=img(!empty($person->thumbnail_url)?$person->thumbnail_url:'/images/nopicture.png')?>
			</div>
			<div class="event_person_name"><?=$person->fullname?></div>
			</a>
		</div>
	<? endforeach; ?>
	
	<? foreach ($entries as $entry): ?> 
		<div class="event_entry">
			<a href="<?=site_url(array('entry', 'view', $entry->id))?>">
			<div class="event_entry_thumbnail">
				<?=img(!empty($entry->thumbnail_url)?$entry->thumbnail_url:'/images/nopicture-entry.png')?>
			</div>
			<div class="event_entry_name"><?=$entry->name?></div>
			<div class="event_entry_division"><?=$entry->divisionname?></div>
			</a>
		</div>
	<? endforeach; ?>

	<div style="clear: both;"></div>
</div>

