<style type="text/css">
	#map_canvas
	{
		position: absolute;
		top: 60px;
		right: 10px;
		left: 10px;
		bottom: 10px;
		box-shadow: 0 0 5px black;
	}
</style>
<div id="map_canvas" style="position:absolute; top:60px; right:10px; left:10px; bottom:10px;">
</div>

<!-- Popups important for the mapview -->
<div class="hide">
	<?php echo $this->element('hover-bubble'); ?>
</div>
<?php echo $this->element('click-bubble'); ?>