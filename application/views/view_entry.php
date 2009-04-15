
<div class="entryview_team">
	<span>Team:</span>
	<a href="<?=site_url(array('team', 'view', $team->id))?>"><?=$team->name?></a>
</div>

<div class="entryview_entry">
	<span>Robot:</span>
	<a href="<?=site_url(array('entry', 'view', $entry->id))?>"><?=$entry->name?></a>
</div>

<div class="entryview_picture">
	<img src="<?=site_url($entry->picture_url)?>" />
</div>


<div class="entryview_entry">
	<span>Competing in the following events:</span>
	<? if (count($events) == 0): ?>
	None
	<? endif; ?>
	<? foreach ($events as $event): ?>
	<div class="entryevent">
		<a href="<?=site_url(array('event', 'view', $event->id))?>"><?=$event->name?></a>
		on <?=date("M j, Y", strtotime($event->date))?>
		in <a href="<?=site_url(array('event', 'entries', $event->id, $event->event_division))?>"><?=$event->division?></a>
		(<?=$event->status?>)
	</div>
	<? endforeach; ?>
</div>


<!--
<br/><br/><pre><? print_r($entry); ?></pre>
<br/><br/><pre><? print_r($team); ?></pre>
<br/><br/><pre><? print_r($events); ?></pre>
-->