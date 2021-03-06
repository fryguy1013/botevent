<?php $this->load->view('view_event_header'); ?>

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
		
	$('form.update_payment_form').submit(function() {
		$.post($(this).attr('action'), {
			amount_paid: $(this).find('input[name=amount_paid]').val(),
            notes: $(this).find('textarea[name=notes]').val()
		}, function (data) { });
		return false;
	});

});

</script>

<? foreach ($event_registrations as $reg): ?>


<div class="event_registration_team">
	<div class="event_reg_minordetails">
		<div style="float: right">
            Status: <?=htmlentities($reg->status)?> | <? if ($reg->paid <= $reg->due): ?>Due: $<?=$reg->due - $reg->paid?><? else:?><b>Overpaid: $<?=$reg->paid - $reg->due?></b><? endif;?>
        </div>
		<?=htmlentities($reg->teamname)?> (<?=htmlentities($reg->teamcountry)?>)
	</div>

	<div class="event_reg_fulldetails" style="display: none;">
		<div class="event_registration_team_name"><?=htmlentities($reg->teamname)?></div>


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

        <? if (isset($event_messages[$reg->teamid])): ?>
        <div class="event_registration_message">
            <? foreach ($event_messages[$reg->teamid] as $message):?>
                <div>
                    <p class="event_registration_message_time"><?=$message->time?></p>
                    <p><?=nl2br(htmlentities($message->message))?></p>
                </div>
            <? endforeach; ?>
        </div>
        <? endif; ?>

		<?=form_open("event/updatepayment/".$reg->id, array('class'=>'update_payment_form'))?>
		<div class="event_reg_payment">
            <div>
				<label for="notes">Notes:</label><br>
                <?=form_textarea(array('name'=>'notes', 'value'=>$reg->notes, 'rows'=>4, 'cols'=>35))?>
			</div>
			<div>
				<label for="amount_paid">Amount Paid:</label><br>
                <?=form_input('amount_paid', $reg->paid, 'size=6')?><br><br>
				<?=form_submit('submit', 'Save')?>
            </div>
        </div>
		<?=form_close()?>
	
		<? foreach ($event_people[$reg->id] as $person): ?> 
			<div class="event_person">
				<a href="<?=site_url(array('person', 'view', $person->id))?>">
				<div class="event_person_thumbnail">
					<?=img(!empty($person->thumbnail_url)?$person->thumbnail_url:'/images/nopicture.png')?>
				</div>
				<div class="event_person_name"><?=htmlentities($person->fullname)?></div>
				</a>
			</div>
		<? endforeach; ?>
		
		<? foreach ($event_entries[$reg->id] as $entry): ?> 
			<div class="event_entry">
				<a href="<?=site_url(array('entry', 'view', $entry->id))?>">
				<div class="event_entry_thumbnail">
					<?=img(!empty($entry->thumbnail_url)?$entry->thumbnail_url:'/images/nopicture-entry.png')?>
				</div>
				<div class="event_entry_name"><?=htmlentities($entry->name)?></div>
				</a>
				<div class="event_entry_division"><?=htmlentities($entry->divisionname)?></div>
				<div class="event_entry_driver">(driver: <?=htmlentities($entry->driver)?>)</div>
			</div>
		<? endforeach; ?>
	
		<div style="clear: both;"></div>

	</div>
</div>

<? endforeach; ?>

