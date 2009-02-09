<div class="event_all_list">

<h2>Upcoming Events</h2>

<? foreach ($events as $event): ?>

<div class="event_all_event">	
	<div class="event_all_image_container">
		<img class="event_all_image" width="100" src="<?=site_url($event->smallimage)?>" alt="<?=$event->name?> logo" />
		<!--<div class="event_all_robots_registered"><? /*=$event->registeredrobots */?> registered</div>-->
	</div>
	<div class="event_all_details_container">
		<div class="event_all_title"><a href="<?=site_url(array('event', 'view', $event->id))?>"><?=$event->name?></a></div>
		<div class="event_all_location"><?=$event->location?></div>
		<div class="event_all_date"><?
			if ($event->startdate != $event->enddate)
				echo date("M j", strtotime($event->startdate))." to ".date("M j, Y", strtotime($event->enddate));
			else
				echo date("M j, Y", strtotime($event->startdate));
			?>
		</div>
		<div class="event_all_registration_ends">
		<?
	$duration = strtotime($event->registrationends) - time();
	if ($duration < 0)
		echo "Closed";
	else
	{
		echo "Registration ends in ";
		if ($duration < 48*60*60)
			echo floor(($duration)/60/60)." hours";
		else if ($duration < 60*60)
			echo floor(($duration)/60)." minutes";
		else
			echo floor(($duration)/60/60/24)." days";
	}
		?>
		</div>
	</div>
	</a>
</div>

<? endforeach; ?>
<div style="clear: both;" />

</div>
