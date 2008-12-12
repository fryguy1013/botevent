<style type="text/css">

.event_details
{
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
	
	<?=$event_header?>
	
	<div class="event_registerbutton">
		<a href="<?=site_url(array('event', 'register', $event->id))?>">Register</a>
	</div>
	
	<div class="event_description">
		<?=$event->description?>
	</div>
</div>
