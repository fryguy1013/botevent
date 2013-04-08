
<div class="teamview_team">
	<a href="<?=site_url(array('team', 'view', $team->id))?>"><?=htmlentities($team->name)?></a>
</div>

<div class="teamview_section">
	<span>Website: </span>
	<?=!empty($team->url) ? anchor($team->url) : "None"?>
</div>

<div class="teamview_section">
	<span>Location: </span>
	<?=htmlentities($team->city)?>, <?=htmlentities($team->state)?>, <?=htmlentities($team->country)?>
</div>
	
<h4>Team Members:</h4>
<div class="teamview_members">
<? foreach ($members as $member): ?>
	<div class="teamview_member">
		<a href="<?=site_url(array('person', 'view', $member->id))?>"><?=htmlentities($member->fullname)?></a>
	</div>
<? endforeach; ?>
</div>

<h4>Entries:</h4>
<div class="event_entries">
<? foreach ($entries as $entry): ?> 
	<div class="event_entry">			
		<div class="event_entry_thumbnail">
			<?=img(!empty($entry->thumbnail_url)?$entry->thumbnail_url:'/images/nopicture-entry.png')?>
		</div>
		<div class="event_entry_name"><a href="<?=site_url(array('entry', 'view', $entry->id))?>"><?=htmlentities($entry->name)?></a></div>
	</div>		
<? endforeach; ?>
</div>
<div style="clear: both;"></div>

<div class="teamview_competed">
	<h4>Competing in the following events:</h4>
	<? if (count($events) == 0): ?>
	None
	<? endif; ?>
	<? foreach ($events as $event): ?>
	<div class="entryevent">
		<div>
			<a href="<?=site_url(array('event', 'view', $event['id']))?>"><?=htmlentities($event['name'])?></a>
			on <?=date("M j, Y", strtotime($event['date']))?>
			(<?=htmlentities($event['status'])?>)
		</div>
		<ul>
		<? foreach ($event['entries'] as $entry): ?>
			<li>
				<a href="<?=site_url(array('entry', 'view', $entry->id))?>"><?=htmlentities($entry->name)?></a>
				in <a href="<?=site_url(array('event', 'entries', $event['id'], $entry->event_division))?>"><?=htmlentities($entry->division)?></a>
			</li>
		<? endforeach; ?>
		</ul>
	</div>
	<? endforeach; ?>
</div>
