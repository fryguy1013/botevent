<div class="event_details">
	
	<div class="event_registerbutton">
		<a href="<?=site_url(array('event', 'register', $event->id))?>">Register</a>
	</div>
	
	<table class="event_divisions">
	<? foreach ($event_divisions as $division): ?>
	
		<tr class="event_division">			
			<td class="event_division_name"><a href="<?=site_url(array('event', 'entries', $event->id, $division->id))?>"><?=$division->name?></a></td>
			<td class="event_division_count"><?=$division->ct?> entries<? if ($division->maxentries != 0) echo " ($division->maxentries max)"; ?></td>
			<td class="event_division_price">$<?=sprintf("%.2f",$division->price)?></td>
		</tr>
	<? endforeach; ?>
	</table>

	<div class="event_description">
		<?=$event->description?>
	</div>

</div>
