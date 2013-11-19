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
	#sublet_introduction
	{
		background-color: #e4eefc;
		border: 12px solid #fff;
		-webkit-box-shadow: 1px 1px 3px #cccccc;
		-moz-box-shadow: 1px 1px 3px #cccccc;
		box-shadow: 1px 1px 3px #cccccc;
		padding: 15px;
		position: absolute;
		right: 10px;
		top: 10px;
		width: 300px;
		z-index: 2;
		color: #5e5e5e;
		text-align: center;
		font-size: 18px;
		line-height: 26px;
	}
	#sublet_introduction .close
	{
		position: absolute;
		top: 1px;
		right: 3px;
	}
	#sublet_introduction .btn
	{
		background-color: #6289cc;
		color: white;
		font-size: 12px;
		font-weight: bold;
		text-shadow: none;
		background-image: none;
		text-transform: uppercase;
		padding-left: 25px;
		padding-right: 25px;
		margin-top: 10px;
	}
</style>
<div id="map_region" data-listing-type="<?= $active_listing_type ?>" data-university-name="<?= $university["name"] ?>" data-university-id="<?= $university["id"] ?>" data-latitude="<?= $university["latitude"] ?>" data-longitude="<?= $university["longitude"] ?>" data-city="<?= $university["city"] ?>" data-state="<?= $university["state"] ?>">
	<?php echo $this->element('filter', array('active_listing_type' => $active_listing_type)); ?>
	<?php echo $this->element('legend'); ?>
	<div id="map_canvas"> <!-- style="height:100%; width:100%;"> -->
	</div>


		<div id="sublet_introduction">
			<button type="button" class="close" onclick="$('#sublet_introduction').fadeOut()">&times;</button>
			<div>
				<?php
				if (empty($university['sublets_launch_date']) || $university['sublets_launch_date'] > date('Y-m-d')) {
					$date_string = $this->Time->nice($university['sublets_launch_date'], null, '%A, %b %eS');
				?>
					Sublets will be launching <?= $date_string ?>!
				<?php
				} else { ?>
					Sublets have launched!
				<?php } ?>
			</div>
			<a href="/sublet/welcome" target="_blank" class="btn">Post my sublet today</a>
		</div>


</div>

<!-- Popups important for the mapview -->
<div class="hide">
	<?php echo $this->element('small-bubble'); ?>
</div>
<?php echo $this->element('large-bubble', array('active_listing_type' => $active_listing_type)); ?>