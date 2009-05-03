<script type="text/javascript">
$(document).ready(function() {
	$('div.event_reg_message').hide();
	$('div.event_reg_status').find('input[type=submit]').attr('disabled', 'disabled');

	$('select[name=status]').change(function() {	
		$(this).siblings('input[type=submit]').attr('disabled', '');
		$(this).parents('div.event_reg_status').find('div.event_reg_message').show();
		//alert($(this).children('[selected]').text());
	});

	$('div.event_reg_status > form').submit(function() {
		$(this).find('div.event_reg_message').hide();
		var t = $(this).find('input[type=submit]');
		$.post($(this).attr('action'), {
			status: $(this).find('select[name=status]').val(),
			message: $(this).find('textarea[name=message]').val(),
			amount_due: $(this).find('input[name=amount_due]').val()
		}, function (data) {
			t.attr('disabled', 'disabled');
		});
		return false;
	});

	$('div.event_reg_fulldetails').hide();
	$('div.event_reg_minordetails').click(function() {
		$('div.event_reg_minordetails').show();
		$('div.event_reg_fulldetails').hide();
	
		$(this).hide();
		$(this).next().show();		
	});
		
	$('div.event_reg_payment > form').submit(function() {
		$.post($(this).attr('action'), {
			amount_paid: $(this).find('input[name=amount_paid]').val(),
		}, function (data) { });
		return false;
	});

});

</script>

<? foreach ($event_registrations as $reg): ?>


<div class="event_registration_team">
	<div class="event_reg_minordetails">
		<div style="float: right">Status: <?=$reg->status?></div>
		<?=$reg->teamname?> (<?=$reg->teamcountry?>)
	</div>

	<div class="event_reg_fulldetails" style="display: none;">
		<div class="event_registration_team_name"><?=$reg->teamname?></div>


		<div class="event_reg_status">
			<?=form_open("event/updatestatus/".$reg->id)?>
			<div>
				Update status:
				<?=form_dropdown('status', array(
					'new' => 'New',
					'pending_payment' => 'Pending Payment',
					'accepted' => 'Accepted',
					'rejected' => 'Rejected'), $reg->status)?>
				Amount Due: <?=form_input('amount_due', $reg->due, 'size=4')?>
				<?=form_submit('submit', 'Change')?>
			</div>
			<div class="event_reg_message">
				<div>Enter Message:</div>
				<div><?=form_textarea(array('name'=>'message', 'value'=>'', 'rows'=>4, 'cols'=>35))?></div>
			</div>			
			<?=form_close()?>
		</div>

		<div class="event_reg_payment">
			<?=form_open("event/updatepayment/".$reg->id)?>
			<div>
				Amount Paid: <?=form_input('amount_paid', $reg->due, 'size=4')?>
				<?=form_submit('submit', 'Change')?>
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

