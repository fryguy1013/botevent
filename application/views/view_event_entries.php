<?php $this->load->view('view_event_header'); ?>

<div class="event_details">

	<h2>Entries in the <?=htmlentities($event_division->name)?> division</h2>
    
    <? if (!empty($event_division->description)): ?>
    <div class="event_description"><?=htmlentities($event_division->description)?></div>
    <? endif; ?>

	<div class="event_entries">
	<? foreach ($event_entries as $entry): ?> 
		<div class="event_entry">			
			<div class="event_entry_thumbnail">
				<div class="event_entry_status">
				<? if ($entry->status == 'new'):?>
					<?=img('/images/entry-pending.png')?>
				<? endif; ?>
				</div>
				<?=img(!empty($entry->thumbnail_url)?$entry->thumbnail_url:'/images/nopicture-entry.png')?>
			</div>
			<div class="event_entry_name"><a href="<?=site_url(array('entry', 'view', $entry->id))?>"><?=htmlentities($entry->name)?></a></div>
			<div class="event_entry_additionaldetails"><?=htmlentities($entry->teamname)?></div>
			<div class="event_entry_additionaldetails"><?=htmlentities($entry->teamcountry)?></div>
		</div>		
	<? endforeach; ?>
	</div>
	
	<div style="clear: both;"></div>

</div>
