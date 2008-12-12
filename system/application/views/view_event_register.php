<style type="text/css">

.event_register_person
{
	width: 500px; 
	border: 1px solid #000;
	padding: 1em;
	clear: both;
	margin-bottom: 1em;
}

h5
{
	margin-top: 1.5em;
	font-size: 11pt;
	margin-bottom: 0px;
}

.event_register_heading
{
	font-weight: bold;
}

.event_register_name
{
	float: left;
}

.event_register_email
{
	float: left;
	margin-left: 1em;
	clear: bottom;
}

.event_register_adult
{
	float: left;
	margin-top: .5em;
}

.event_register_picture
{
	float: right;
	margin-right: 10px;
	text-align: center;
}

.event_register_picture > img
{
	width: 40px;
	margin: 0px;
}

.event_register_picture_upload
{
	margin-top: 1em;
	display: none;
}




.event_register_entry
{
	width: 500px; 
	border: 1px solid #000;
	padding: 1em;
	clear: both;
	margin-bottom: 1em;
}

.event_register_entry_name
{
	float: left;
}

.event_register_entry_division
{
	float: left;
	margin-left: 1em;
}

</style>

<?=$event_header?>

<?=form_open_multipart("event/register/".$event->id)?>

<?=validation_errors()?>

<div id="event_register_competitors">
<? foreach ($person as $id=>$p): ?>
	<? if (!empty($p['heading'])): ?>
		<h5><?=$p['heading']?></h5>
	<? endif; ?>
	<div class="event_register_person">	
		<div class="event_register_picture">
			<?=img($p['picture'])?>
			<div><?=anchor('', 'Upload')?></div>
		</div>
		<div class="event_register_name">
			<div class="event_register_heading">Full Name:</div>
			<?=form_input("person[${id}][fullname]", $p['fullname'])?>
		</div>
		<div class="event_register_email">
			<div class="event_register_heading">Email:</div>
			<?=form_input("person[${id}][email]", $p['email'], 'size="35"')?>
		</div>	
		<div class="event_register_adult">
			<?=form_checkbox("person[${id}][adult]", 'is_adult', $p['adult'])?>
			<span>Is at least 18 years of age</span>
		</div>
		<div style="clear: both;"></div>
	</div>
<? endforeach; ?>
</div>


<div id="event_register_competitors">
	<h5>Please enter information about the entries.</h5>
	<? for ($i=0; $i<4; $i++): ?>
	<div class="event_register_entry">
		<!--
		<div class="event_register_picture">
			<?=img($you_picture)?>
			<div><a href="#" onclick="return false">Upload</a></div>
		</div>
		-->
		<div class="event_register_entry_name">
			<div class="event_register_heading">Entry Name:</div>
			<?=form_input("entry[${i}][name]", '')?>
		</div>
		<div class="event_register_entry_division">
			<div class="event_register_heading">Division:</div>
			<?=form_dropdown("entry[${i}][division]", $event_divisions)?>
		</div>
		
		<div style="clear: both;"></div>
	</div>
	<? endfor; ?>
</div>

<?=form_submit('submit', 'Register')?>

<?=form_close()?>