<script type="text/javascript">
$(document).ready(function() {
	$('div.event_reg_message').hide();
	$('div.event_reg_status').find('input[type=submit]').attr('disabled', 'disabled');

	$('select[name=change_status]').change(function() {	
		$(this).siblings('input[type=submit]').attr('disabled', '');
		$(this).parents('div.event_reg_status').find('div.event_reg_message').show();
		//alert($(this).children('[selected]').text());
	});
	
	$('form').submit(function() {
		$(this).find('div.event_reg_message').hide();
		var t = $(this).find('input[type=submit]');
		$.post($(this).attr('action'), {
			status: $(this).find('select[name=change_status]').children('[selected]').text()
		}, function (data) {
			t.attr('disabled', 'disabled');
		});
		return false;
	});
	
	$('div.event_reg_fulldetails').hide();
	$('div.event_reg_minordetails').click(function() {
		$(this).hide();
		$(this).next().show();
	});
	
});

</script>

<? foreach ($event_registrations as $reg): ?>


<div class="event_registration_team">
	<div class="event_reg_minordetails">
		<div style="float: right">Status: <?=$reg->status?></div>
		<?=$reg->teamname?>
	</div>

	<div class="event_reg_fulldetails">
		<div class="event_registration_team_name"><?=$reg->teamname?></div>


		<div class="event_reg_status">
			<?=form_open("event/updatestatus/".$reg->id)?>
			<div>
				Update status:
				<?=form_dropdown('change_status', array('new', 'pending_payment', 'accepted', 'rejected'), $reg->status)?>
				<?=form_submit('submit', 'Change')?>
			</div>
			<div class="event_reg_message">
				<div>Enter Message:</div>
				<div><?=form_textarea(array('name'=>'change_email', 'value'=>'', 'rows'=>4, 'cols'=>35))?></div>
			</div>			
			<?=form_close()?>
		</div>	
	
		<? foreach ($event_people[$reg->id] as $person): ?> 
			<div class="event_person">
				<a href="<?=site_url(array('person', 'view', $person->id))?>">
				<div class="event_person_thumbnail">
					<?=img(!empty($person->thumbnail_url)?$person->thumbnail_url:'/images/nopicture.png')?>
				</div>
				<div class="event_person_name"><?=$person->fullname?></div>
				</a>
			</div>
		<? endforeach; ?>
		
		<? foreach ($event_entries[$reg->id] as $entry): ?> 
			<div class="event_entry">
				<a href="<?=site_url(array('entry', 'view', $entry->id))?>">
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
</div>

<? endforeach; ?>

