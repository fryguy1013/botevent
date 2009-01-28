<style type="text/css">

.event_entry
{
	width: 150px;
	border: 1px solid #000;
	background: #eee;
	float: left;
	margin: .5em;
	padding: .5em;
	text-align: center;
}

.event_entry_thumbnail img
{
	width: 150px;
}

.event_entry_status
{
	position: absolute;	
}
.event_entry_status img
{
	width: 150px;	
}

.event_details > h2
{
	text-align: center;
	
}

</style>

<div class="event_details">

	<h2>Entries in the <?=$event_division->name?> division</h2>

	<div class="event_entries">
	<? foreach ($event_entries as $entry): ?> 
		<div class="event_entry">
			<a href="<?=site_url(array('entry', $entry->id))?>">
			<div class="event_entry_thumbnail">
				<div class="event_entry_status">
				<? if ($entry->status == 'new'):?>
					<?=img('/images/entry-pending.png')?>
				<? endif; ?>
				</div>
				<?=img(!empty($entry->thumbnail)?$entry->thumbnail:'/images/nopicture-entry.png')?>
			</div>
			<div class="event_entry_name"><?=$entry->name?></div>
			</a>
		</div>		
	<? endforeach; ?>
	</div>
	
	<div style="clear: both;"></div>

</div>
