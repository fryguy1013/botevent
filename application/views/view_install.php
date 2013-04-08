
<div class="databaseversion">
	Your current database version is <?=$dbversion?>.
</div>
<? if ($dbversion < $maxupdateversion): ?>
	<div>
		<form action="<?=site_url(array('install', 'commit', $dbversion+1))?>" method="post">
			<input type="submit" value="Update to <?=$dbversion + 1?>">
		</form>
	</div>
<? endif; ?>
<? if ($dbversion > 0): ?>
	<div>
		<form action="<?=site_url(array('install', 'rollback', $dbversion-1))?>" method="post">
			<input type="submit" value="Rollback to <?=$dbversion - 1?>">
		</form>
	</div>
<? endif; ?>

<div>
	<form action="<?=site_url(array('install', 'reset'))?>" method="post">
		<input type="submit" value="Reset database data (development only)">
	</form>
</div>
