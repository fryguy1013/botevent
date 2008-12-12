<style type="text/css">
.event_major_details
{
	text-align: center;
	font-size: 11pt; 
}

.event_title
{
	font-size: 18pt;
	margin: .25em;
	font-weight: bold;
}

.event_major_details span
{
	color: LimeGreen;
	font-weight: bold;
}
</style>

<div class="event_major_details">
	<img class="event_image" src="<?=$event->image?>" alt="<?=$event->name?> logo" />
	<div class="event_title"><?=$event->name?></div>
	<div class="event_location"><span>Location: </span><?=$event->location?></div>
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