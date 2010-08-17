<div class="event_major_details">
	<a href="<?=site_url(array('event', 'view', $event->id))?>">
		<img class="event_image" src="<?=site_url($event->image)?>" alt="<?=htmlentities($event->name)?> logo" />
	</a>
	<div class="event_minor_details">		
		<div class="event_title"><a href="<?=site_url(array('event', 'view', $event->id))?>"><?=htmlentities($event->name)?></a></div>
		<div class="event_location"><span>Location: </span><?=htmlentities($event->location)?></div>
		<div class="event_date"><span>Date: </span><?
			if ($event->startdate != $event->enddate)
				echo date("M j", strtotime($event->startdate))." to ".date("M j, Y", strtotime($event->enddate));
			else
				echo date("M j, Y", strtotime($event->startdate));
		?></div>
		<div class="event_registration_ends"><span>Registration: </span>
		<?
		$duration = strtotime($event->registrationends) - time();
		if ($duration < 0)
			echo "Closed";
		else
			echo "Ends in ".timeuntil($duration)." (".date("M j, Y", strtotime($event->registrationends)).")";
		?>
		</div>
	</div>
</div>