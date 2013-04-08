<?php $this->load->view('view_event_header'); ?>

<div class="event_details">
	
	<? if (!empty($registration_status)): ?>
	<div class="event_registerbutton">
		<a href="<?=site_url(array('event_registration', 'view', $registration_status->id))?>">View Registration Status</a>
	</div>
	<? elseif ($registration_available): ?>
	<div class="event_registerbutton">
		<a href="<?=site_url(array('event', 'register', $event->id))?>">Register</a>
	</div>
	<? elseif ($this->session->userdata('userid') === false): ?>
	<? $this->session->set_userdata('onloginurl', site_url(array('event', 'register', $event->id))); ?>
	<div class="event_registerbutton">
		<a href="<?=site_url(array('login'))?>">Login to see registration status</a>
	</div>
	<? endif; ?>
	
	<table class="event_divisions">
	<? foreach ($event_divisions as $division): ?>
	
		<tr class="event_division">			
			<td class="event_division_name"><a href="<?=site_url(array('event', 'entries', $event->id, $division->event_division))?>"><?=htmlentities($division->name)?></a></td>
			<td class="event_division_count"><?=isset($event_division_counts[$division->event_division]) ? $event_division_counts[$division->event_division] : 0?> entries<? if ($division->maxentries != 0) echo " ($division->maxentries max)"; ?></td>
			<td class="event_division_price"><?=$division->price == 0 ? "Free" : sprintf("\$%d",$division->price)?></td>
		</tr>
	<? endforeach; ?>
	</table>

	<div class="event_description">
		<?=htmlentities($event->description)?>
	</div>

</div>
