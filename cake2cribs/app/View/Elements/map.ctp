<?= $this->Html->script('src/Map', array('inline' => false)); ?>

<style type="text/css">
	#map_region
	{
		position: absolute;
		top: 60px;
		right: 370px;
		left: 10px;
		bottom: 10px;
		box-shadow: 0 0 5px black;
	}
</style>
<div id="map_region">
	<?php echo $this->element('filter'); ?>
	<div id="map_canvas" style="height:100%; width:100%;">
	</div>
</div>

<!-- Popups important for the mapview -->
<div class="hide">
	<?php echo $this->element('hover-bubble'); ?>
</div>
<?php echo $this->element('click-bubble'); ?>