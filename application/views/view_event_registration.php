<style type="text/css">

.event_entry
{
	width: 125px;
	border: 1px solid #000;
	background: #eee;
	float: left;
	margin: .5em;
	padding: .5em;
	text-align: center;
}

.event_entry_thumbnail img
{
	width: 125px;
}

.event_person
{
	width: 100px;
	border: 1px solid #000;
	background: #eee;
	float: left;
	margin: .5em;
	padding: .5em;
	text-align: center;
}

.event_person_thumbnail img
{
	width: 100px;
}

.event_registration_team
{
	margin: 1em;
	border: 1px solid #000;
	background: #ddd;
}

.event_registration_status
{
	font-size: 20pt;
	margin-left: 1em;
}

</style>

<script type="text/javascript">
$(document).ready(function() {	
});

</script>

<div class="event_registration_team">
	<div class="event_registration_status">
		Status: <?=$registration->status?>
	</div>

	<? foreach ($people as $person): ?> 
		<div class="event_person">
			<a href="<?=site_url(array('person', $person->id))?>">
			<div class="event_person_thumbnail">
				<?=img(!empty($person->thumbnail_url)?$person->thumbnail_url:'/images/nopicture.png')?>
			</div>
			<div class="event_person_name"><?=$person->fullname?></div>
			</a>
		</div>
	<? endforeach; ?>
	
	<? foreach ($entries as $entry): ?> 
		<div class="event_entry">
			<a href="<?=site_url(array('entry', $entry->id))?>">
			<div class="event_entry_thumbnail">
				<?=img(!empty($entry->thumbnail_url)?$entry->thumbnail_url:'/images/nopicture-entry.png')?>
			</div>
			<div class="event_entry_name"><?=$entry->name?></div>
			<div class="event_entry_division"><?=$entry->divisionname?></div>
			</a>
		</div>
	<? endforeach; ?>

	<div style="clear: both;"></div>
</div>

