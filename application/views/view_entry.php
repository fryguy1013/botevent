
<div class="entryview_entry">
	<!--<span>Robot:</span>-->
	<a href="<?=site_url(array('entry', 'view', $entry->id))?>"><?=htmlentities($entry->name)?></a>
</div>

<div class="entryview_team">
	<span>Team:</span>
	<a href="<?=site_url(array('team', 'view', $team->id))?>"><?=htmlentities($team->name)?></a>
</div>

<div class="entryview_picture">
	<?=img(!empty($entry->picture_url)?$entry->picture_url:'/images/nopicture-entry.png')?>
</div>


<div class="entryview_competed">
	<span>Competing in the following events:</span>
	<? if (count($events) == 0): ?>
	None
	<? endif; ?>
	<? foreach ($events as $event): ?>
	<div class="entryevent">
		<a href="<?=site_url(array('event', 'view', $event->id))?>"><?=htmlentities($event->name)?></a>
		on <?=date("M j, Y", strtotime($event->date))?>
		in <a href="<?=site_url(array('event', 'entries', $event->id, $event->event_division))?>"><?=htmlentities($event->division)?></a>
		(<?=htmlentities($event->status)?>)
	</div>
	<? endforeach; ?>
</div>