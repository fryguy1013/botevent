
<div class="databaseversion">
	Your current database version is <?=$dbversion?>.
</div>
<? if ($dbversion < $maxupdateversion): ?>
	<div>
	    <a href="<?=site_url(array('install', 'commit', $dbversion+1))?>">Update to <?=$dbversion + 1?></a>
	</div>
<? endif; ?>
<? if ($dbversion > 0): ?>
	<div>
	    <a href="<?=site_url(array('install', 'rollback', $dbversion-1))?>">Rollback to <?=$dbversion - 1?></a>
	</div>
<? endif; ?>

<div>
    <a href="<?=site_url(array('install', 'reset'))?>">Reset database data (development only)</a>
</div>
