<style type="text/css">

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

.event_divisions
{
	margin-left: auto;
	margin-right: auto;	
	padding: .5em;
	border: 1px solid black;
	width: 30em;
	background: #eee;
}

.event_division
{
	font-size: 12pt;
	margin-top: .125em;
	margin-bottom: .125em;
}

.event_division_name
{
	width: 15em;
	color: LimeGreen;
	float: left;
	clear: both;
	font-weight: bold;
}

.event_description
{
	margin-top: 1em;
}

</style>

<div class="event_details">
	
	<div class="event_registerbutton">
		<a href="<?=site_url(array('event', 'register', $event->id))?>">Register</a>
	</div>
	
	<div class="event_divisions">
	<? foreach ($event_divisions as $division): ?>
		<div class="event_division">
			<a href="<?=site_url(array('event', 'entries', $event->id, $division->id))?>">
			<div class="event_division_name"><?=$division->name?></div>
			<div class="event_division_count"><?=$division->ct?> entries<? if ($division->maxentries != 0) echo " ($division->maxentries max)"; ?></div>
			</a>
		</div>
	<? endforeach; ?>
	</div>

	<div class="event_description">
		<?=$event->description?>
	</div>

</div>
