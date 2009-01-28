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
	width: 39em;
	background: #eee;
}

.event_division
{
	font-size: 12pt;
	margin-top: .125em;
	margin-bottom: .125em;
}

.event_division_name a
{
	width: 15em;
	color: LimeGreen;
	float: left;
	font-weight: bold;
	text-decoration: none;
}

.event_division_count
{
}

.event_division_price
{
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
	
	<table class="event_divisions">
	<? foreach ($event_divisions as $division): ?>
	
		<tr class="event_division">			
			<td class="event_division_name"><a href="<?=site_url(array('event', 'entries', $event->id, $division->id))?>"><?=$division->name?></a></td>
			<td class="event_division_count"><?=$division->ct?> entries<? if ($division->maxentries != 0) echo " ($division->maxentries max)"; ?></td>
			<td class="event_division_price">$<?=sprintf("%.2f",$division->price)?></td>
		</tr>
	<? endforeach; ?>
	</table>

	<div class="event_description">
		<?=$event->description?>
	</div>

</div>
