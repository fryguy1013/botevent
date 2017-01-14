<?php $this->load->view('view_event_header'); ?>

<div class="event_registration">
	<div class="event_registration_status">
		Status: <?=htmlentities($registration->status)?>
	</div>
	<div class="event_registration_status_description">
		<? if ($registration->status == "new"): ?>
		Your registration is pending. The event organizer will review it, and
		accept or reject it. You will get an email when this happens, and at
		that point, you will be able to pay for your entry.
		<? elseif ($registration->status == "pending_payment"): ?>
		<p>Your registration has been accepted, and you may pay now. Note that
		payments are handled manually for now, so your status may not change
		from pending payment to accepted immediately.</p>
		
		<h4>Amount due: $<?=htmlentities($registration->due)?></h4>
		
		<div class="pay_by_cc">
			Pay by credit card
			<form method="POST" action="http://www.asecurecart.net/server/cart.aspx/robogames">
			<input type="hidden" name="Price" value="<?=htmlentities($registration->due)?>">
			<input type="hidden" name="ID" value="<?=htmlentities(substr('Reg Fees - '.$registration->teamname, 0, 30))?>">
			<input type="hidden" name="Multi" value="N">
			<input type="hidden" name="ReturnLink" value="http://robogames.net/registration/event_registration/view/<?=htmlentities($registration->id)?>">
			<input type="submit" name="Submit" value="Add To Cart">
			</form>
		</div>
		
		<? elseif ($registration->status == "accepted"): ?>
		Your registration has been accepted, and you have paid. See you at the
		event!
		<? endif; ?>
	</div>

    <? if ($is_member): ?>
    <? // only show change register button if registration is open ?>
    <? if (strtotime($event->registrationends) > time()): ?>
	<div class="event_registerbutton">
		<a href="<?=site_url(array('event', 'register', $event->id, 'update'))?>">Change Registration</a>
	</div>
    <? endif; ?>
	<div class="event_withdrawbutton">
		<a href="<?=site_url(array('event_registration', 'withdraw', $registration->id))?>">Withdraw</a>
	</div>
    <? endif; ?>


	<? foreach ($people as $person): ?> 
		<div class="event_person">
			<a href="<?=site_url(array('person', 'view', $person->id))?>">
			<div class="event_person_thumbnail">
				<?=img(!empty($person->thumbnail_url)?$person->thumbnail_url:'/images/nopicture.png')?>
			</div>
			<div class="event_person_name"><?=htmlentities($person->fullname)?></div>
			</a>
		</div>
	<? endforeach; ?>
	
	<? foreach ($entries as $entry): ?> 
		<div class="event_entry">
			<a href="<?=site_url(array('entry', 'view', $entry->id))?>">
			<div class="event_entry_thumbnail">
				<?=img(!empty($entry->thumbnail_url)?$entry->thumbnail_url:'/images/nopicture-entry.png')?>
			</div>
			<div class="event_entry_name"><?=htmlentities($entry->name)?></div>
			</a>
			<div class="event_entry_division"><?=htmlentities($entry->divisionname)?></div>
			<div class="event_entry_driver">(driver: <?=htmlentities($entry->driver)?>)</div>
		</div>
	<? endforeach; ?>

	<div style="clear: both;"></div>
</div>

