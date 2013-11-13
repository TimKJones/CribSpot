<?php
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	echo  $this->Html->script('src/Map', array('inline' => false));
}
?>

<style type="text/css">
	#layoutsContainer
	{
		background-color: #dedfdf;
	}
	#map_region
	{
		position: absolute;
		top: 57px;
		right: 362px;
		left: 12px;
		bottom: 12px;
		box-shadow: 0 0 5px gray;
	}
	#map_canvas
	{
		position: absolute;
		top: 0;
		right: 0;
		left: 0;
		bottom: 0;
	}
</style>
<div id="map_region">
	<?php echo $this->element('filter'); ?>
	<?php echo $this->element('legend'); ?>
	<div id="map_canvas"> <!-- style="height:100%; width:100%;"> -->
	</div>
</div>

<!-- Popups important for the mapview -->
<div class="hide">
	<?php echo $this->element('small-bubble'); ?>
</div>
<?php echo $this->element('large-bubble'); ?>