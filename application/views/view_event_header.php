<style type="text/css">

img
{
	border: 0;
}

.event_major_details
{
	text-align: center;
	font-size: 11pt;
}

.event_minor_details
{
	margin: 1em; 
	border: 1px solid #000;
	width: 30em;
	margin-left: auto;
	margin-right: auto;
	padding: .5em;
	background: #eee;	
}

.event_minor_details a
{
	color: #000;
	text-decoration: none;
}

.event_title
{
	font-size: 18pt;
	margin: .25em;
	margin-top: 0;
	font-weight: bold;
}

.event_major_details span
{
	color: LimeGreen;
	font-weight: bold;
}
</style>

<div class="event_major_details">
	<a href="<?=site_url(array('event', 'view', $event->id))?>">
		<img class="event_image" src="<?=$event->image?>" alt="<?=$event->name?> logo" />
	</a>
	<div class="event_minor_details">		
		<div class="event_title"><a href="<?=site_url(array('event', 'view', $event->id))?>"><?=$event->name?></a></div>
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
</div>