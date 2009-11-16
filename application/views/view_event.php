<div class="event_details">
	
	<? if ($registration_available): ?>
	<div class="event_registerbutton">
		<a href="<?=site_url(array('event', 'register', $event->id))?>">Register</a>
	</div>
	<? endif; ?>
	
	<table class="event_divisions">
	<? foreach ($event_divisions as $division): ?>
	
		<tr class="event_division">			
			<td class="event_division_name"><a href="<?=site_url(array('event', 'entries', $event->id, $division->event_division))?>"><?=$division->name?></a></td>
			<td class="event_division_count"><?=isset($event_division_counts[$division->event_division]) ? $event_division_counts[$division->event_division] : 0?> entries<? if ($division->maxentries != 0) echo " ($division->maxentries max)"; ?></td>
			<td class="event_division_price"><?=$division->price == 0 ? "Free" : sprintf("\$%d",$division->price)?></td>
		</tr>
	<? endforeach; ?>
	</table>

	<div class="event_description">
		<?=$event->description?>
	</div>

</div>
