<?php $this->load->view('view_event_header'); ?>

<div class="event_registration">
	<div class="event_registration_status">
		Status: <?=$registration->status?>
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
		
		<h4>Amount due: $<?=$registration->due?></h4>
		
		<div class="pay_by_cc">
			Pay by credit card
			<form method="POST" action="http://www.asecurecart.net/server/cart.aspx/robogames">
			<input type="hidden" name="Price" value="<?=$registration->due?>">
			<input type="hidden" name="ID" value="<?=substr('Reg Fees - '.$registration->teamname, 0, 30)?>">
			<input type="hidden" name="Multi" value="N">
			<input type="hidden" name="ReturnLink" value="http://robogames.net/registration/event_registration/view/<?=$registration->id?>">
			<input type="submit" name="Submit" value="Add To Cart">
			</form>
		</div>
		
		<div class="pay_by_paypal">
			Pay with paypal
			<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but22.gif" border="0" name="submit">
			<input type="hidden" name="add" value="1">
			<input type="hidden" name="cmd" value="_cart">
			<input type="hidden" name="business" value="paypal@robogames.net">
			<input type="hidden" name="item_name" value="Registration Fees for <?=$registration->teamname?> ">
			<input type="hidden" name="amount" value="<?=$registration->due?>">
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="no_shipping" value="1">
			<input type="hidden" name="return" value="http://robogames.net/registration/event_registration/view/<?=$registration->id?>">
			<input type="hidden" name="currency_code" value="USD">
			</form>
		</div>
		
		<? elseif ($registration->status == "accepted"): ?>
		Your registration has been accepted, and you have paid. See you at the
		event!
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

