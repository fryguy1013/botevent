<style type="text/css">

body
{
	font: normal 9pt arial, helvetica, sans-serif;
}

.event_details
{
	width: 85%;
	border: 1px solid #000;
	clear: both;
}

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

.event_registerbutton
{
	margin: 10px;
	text-align: center;
}

.event_registerbutton a
{
	border: 1px solid LimeGreen;
	background-color: GreenYellow;
	color: #000;
	padding: 3px;
	margin: 10px;
}

</style>

<div class="event_details">

	<div class="event_major_details">
		<img class="event_image" src="<?=$event->image?>" alt="<?=$event->name?> logo" />
		<div class="event_title"><?=$event->name?></div>
		<div class="event_location"><span>Location: </span><?=$event->location?></div>
		<div class="event_date"><span>Date: </span><?
		if ($event->startdate != $event->enddate)
			echo date("M j", strtotime($event->startdate))." to ".date("M j, Y", strtotime($event->enddate));
		else
			echo date("M j, Y", strtotime($event->startdate));
				?>
		</div>
		<div class="event_registration_ends"><span>Registration: </span>
		<?
		$duration = strtotime($event->registrationends) - time();
		if ($duration < 0)
			echo "Closed";
		else
		{
			echo "Ends in ";
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
	
	<div class="event_registerbutton">
		<a href="<?=site_url(array('event', 'register', $event->id))?>">Register</a>
	</div>
	
	<div class="event_description">
		<?=$event->description?>
	</div>
</div>
