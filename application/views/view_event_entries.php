<style type="text/css">

.event_entry
{
	width: 10em;
	border: 1px solid #000;
	background: #eee;
	float: left;
	margin: .5em;
	padding: .5em;
	text-align: center;
}

.event_entry_thumbnail img
{
	width: 10em;
	border: 0;
}

</style>

<div class="event_details">

	<div>Entries in the <?=$event_division->name?> division</div>

	<div class="event_entries">
	<? foreach ($event_entries as $entry): ?> 
		<div class="event_entry">
			<a href="<?=site_url(array('entry', $entry->id))?>">
			<div class="event_entry_thumbnail"><?=img(!empty($entry->thumbnail)?$entry->thumbnail:'images/nopicture-entry.png')?></div>
			<div class="event_entry_name"><?=$entry->name?></div>
			<div class="event_entry_teamname"><?=$entry->teamname?></div>
			<div class="event_entry_status"><?=$entry->status?></div>
			</a>
		</div>		
	<? endforeach; ?>
	</div>
	
	<div style="clear: both;"></div>

</div>
