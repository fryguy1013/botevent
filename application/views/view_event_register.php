<?php
  $thumbnail_width = 80;
  $thumbnail_height = 100;
?>

<style type="text/css">

h5
{
	margin-top: 1.5em;
	font-size: 11pt;
	margin-bottom: 0px;
}

.event_register_block
{
	width: 400px; 
	border: 1px solid #000;
	background: #eee;
	padding: 1em;
	margin-right: 2em;
	margin-bottom: 1em;
	-moz-border-radius: 20px;
	border-radius: 20px;
	float: left;
}

.event_register_heading
{
	font-weight: bold;
}

.event_register_team_website
{
  margin-top: .5em;
}

.event_register_email
{
  margin-top: .5em;
}

.event_register_adult
{
	float: left;
	margin-top: .5em;
}

.event_register_picture_frame
{
	float: right;
	margin-right: 10px;
	text-align: center;
}

.event_register_picture
{
	width: <?=$thumbnail_width?>px;
	height: <?=$thumbnail_height?>px;
	margin: 0px;
}

.event_register_image_frame
{
  overflow: hidden;
  margin-left: 5px;
  width: <?=$thumbnail_width?>px;
  height: <?=$thumbnail_height?>px;
}

.event_register_picture_upload
{
	margin-top: 1em;
	display: none;
}

.event_register_entry_division
{
	margin-top: .5em;
}

#event_register_entries
{
  clear: both;
}

.event_register_entry_block
{
  width: 248px;
}

</style>

<?=form_open_multipart("event/register/".$event->id)?>

<?=validation_errors()?>

<h5>Enter information about your team:</h5>
<div class="event_register_block">
	<div class="event_register_team_name">
		<div class="event_register_heading">Team Name:</div>
		<?=form_input("team[name]", $team['name'])?>		
	</div>

	<div class="event_register_team_website">
		<div class="event_register_heading">Website:</div>
		<?=form_input("team[website]", $team['website'], 'size="35"')?>
	</div>

</div>

<script type="text/javascript">
function upload_picture_with_div(id)
{
	return function() {

	$.ajax_upload('#upload_picture_' + id, {	  
		action: '<?=site_url('/event/uploadpicture')?>',
		name: 'photo',
		data: { },
		onSubmit: function(file, extension)
		{
		},
		onComplete: function(file, response)
		{
		},
		onSuccess: function(file, response)
		{
			response = response.substring(9);

			$('#upload_picture_' + id + '_picture').attr('src', response); 
			
			jQuery.facebox(
				'<div>' +
				'	Please select the area of your face, only' +
				'</div>' +
				'<div style="text-align: center;">' +
				'	<img src="' + response + '" id="photo_crop_' + id + '" style="max-width: 450px;"/>' +
				'</div>');
			
			$('#photo_crop_' + id).Jcrop({
				aspectRatio: .80,
				boxWidth: 450,
				onChange: function(c)
				{
					$('#person\\[' + id + '\\]\\[picturecropx\\]').val(c.x);
					$('#person\\[' + id + '\\]\\[picturecropy\\]').val(c.y);
					$('#person\\[' + id + '\\]\\[picturecropwidth\\]').val(c.w);
					$('#person\\[' + id + '\\]\\[picturecropheight\\]').val(c.h);
					
					var rx = <?=$thumbnail_width?> / c.w;
					var ry = <?=$thumbnail_height?> / c.h;					
					$('#upload_picture_' + id + '_picture').css({
						width: Math.round(rx * $('#photo_crop_' + id).attr('width')) + 'px',
						height: Math.round(ry * $('#photo_crop_' + id).attr('height')) + 'px',
						marginLeft: '-' + Math.round(rx * c.x) + 'px',
						marginTop: '-' + Math.round(ry * c.y) + 'px'
					});
				}
			});
			
			$('#person\\[' + id + '\\]\\[picturepath\\]').val(response);
			
		},
		onError: function(file, response)
		{
			jQuery.facebox('Error occurred uploading picture: ' + response);
		}
	});

	};
}
</script>

<div id="event_register_competitors">
<? foreach ($person as $id=>$p): ?>
	<? if (!empty($p['heading'])): ?>
		<h5 style="clear: both;"><?=$p['heading']?></h5>
	<? endif; ?>
	<input type="hidden" name="person[<?=$id?>][picturepath]" id="person[<?=$id?>][picturepath]" value="<?=$p['picturepath']?>" />
	<input type="hidden" name="person[<?=$id?>][picturecropx]" id="person[<?=$id?>][picturecropx]" value="<?=$p['picturecropx']?>" />
	<input type="hidden" name="person[<?=$id?>][picturecropy]" id="person[<?=$id?>][picturecropy]" value="<?=$p['picturecropy']?>" />
	<input type="hidden" name="person[<?=$id?>][picturecropwidth]" id="person[<?=$id?>][picturecropwidth]" value="<?=$p['picturecropwidth']?>" />
	<input type="hidden" name="person[<?=$id?>][picturecropheight]" id="person[<?=$id?>][picturecropheight]" value="<?=$p['picturecropheight']?>" />

	<div class="event_register_block">	
		<div class="event_register_picture_frame">
			<div id="upload_picture_<?=$id?>">
				<div class="event_register_image_frame">
					<img id="upload_picture_<?=$id?>_picture" src="<?=$p['picture']?>" class="event_register_picture" />
				</div>
				<div>Upload</div>
			</div>
		</div>
		<div class="event_register_name">
			<div class="event_register_heading">Full Name:</div>
			<?=form_input("person[${id}][fullname]", $p['fullname'])?>
		</div>
		<div class="event_register_email">
			<div class="event_register_heading">Email:</div>
			<?=form_input("person[${id}][email]", $p['email'], 'size="35"')?>
		</div>	
		<div class="event_register_adult">
			<label>
				<?=form_checkbox("person[${id}][adult]", 'is_adult', $p['adult'])?>
				<span>Is at least 18 years of age</span>
			</label>
		</div>
		<div style="clear: both;"></div>
	</div>
	
	<script type="text/javascript">
	$(document).ready(upload_picture_with_div(<?=$id?>));
	</script>	
<? endforeach; ?>
</div>


<div id="event_register_entries">
	<h5>Please enter information about the entries.</h5>
	<? foreach ($entries as $i=>$entry): ?>
	<div class="event_register_block event_register_entry_block">
		<div class="event_register_entry_name">
			<div class="event_register_heading">Entry Name:</div>
			<?=form_input("entry[${i}][name]", $entry['name'])?>
		</div>
		<div class="event_register_entry_division">
			<div class="event_register_heading">Division:</div>
			<?=form_dropdown("entry[${i}][division]", $event_divisions, $entry['division'])?>
		</div>
		
		<div style="clear: both;"></div>
	</div>
	<? endforeach; ?>
</div>

<div style="clear: both;"></div>

<?=form_submit('submit', 'Register')?>

<?=form_close()?>