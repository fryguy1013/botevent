<? foreach ($robots as $robot): ?>
<!-- <? print_r($robot); ?> -->

<div class="robot">
	<img src="<?=$robot->thumbnail?>" />
	<div><?=$robot->name?></div>
	<div><?=$robot->description?></div>
</div>

<? endforeach; ?>	