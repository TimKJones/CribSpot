<?php echo $this->Html->css('/less/Filter/mobile_filter.less?v=1','stylesheet/less', array('inline' => false)); ?>

<?php
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	echo $this->Html->script('src/FilterManager', array('inline' => false));
	echo $this->Html->script('src/MobileFilter', array('inline' => false));
}

$listing_types = array('Rentals', 'Sublets');
$active_listing = $listing_types[$active_listing_type];
?>

<div id="mobile_filter" data-university-name="<?=str_replace(" ", "_", $university_name)?>">
	<div class="container">
		<div class="filter_div">
			<div class="filter_item">
				<select id='listing_type'>
					<? foreach($listing_types as $type) { ?>
							<? if ($type == $active_listing) {
								?><option selected><?=$type?></option><?
							}
							else {
								?><option><?=$type?></option><?
							}
					} ?>
				</select>
			</div>

			<div class="filter_item">
				<select id='bedrooms'>
					<option data-min="0" data-max="1000">Beds</option>
					<option data-min="1" data-max="1">1</option>
					<option data-min="2" data-max="4">2-4</option>
					<option data-min="5" data-max="1000">5+</option>
				</select>
			</div>

			<div class="filter_item">
				<select id='rent'>
					<option data-min="0" data-max="20000">Rent</option>
					<option data-min="0" data-max="500">&lt;500</option>
					<option data-min="500" data-max="1000">500-1000</option>
					<option data-min="1000" data-max="20000">1000+</option>
				</select>
			</div>
		</div>
	</div>
</div>

<?php $this->Js->buffer('A2Cribs.MobileFilter.SetupUI();'); ?>
