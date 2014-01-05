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
					<option data-min="2" data-max="2">2</option>
					<option data-min="3" data-max="3">3</option>
					<option data-min="4" data-max="4">4</option>
					<option data-min="5" data-max="5">5</option>
					<option data-min="6" data-max="6">6</option>
					<option data-min="7" data-max="7">7</option>
					<option data-min="8" data-max="8">8</option>
					<option data-min="9" data-max="9">9</option>
					<option data-min="10" data-max="1000">10+</option>
				</select>
			</div>

			<div class="filter_item">
				<select id='rent'>
					<option data-min="0" data-max="20000">Rent</option>
					<option data-min="0" data-max="500">&lt;500</option>
					<option data-min="500" data-max="1000">500-1000</option>
					<option data-min="1000" data-max="1500">1000-1500</option>
					<option data-min="1500" data-max="2000">1500-2000</option>
					<option data-min="2000" data-max="2500">2000-2500</option>
					<option data-min="2500" data-max="3000">2500-3000</option>
					<option data-min="3000" data-max="3500">3000-3500</option>
					<option data-min="3500" data-max="4000">3500-4000</option>
					<option data-min="4000" data-max="20000">4000+</option>
				</select>
			</div>
		</div>
	</div>
</div>

<?php $this->Js->buffer('A2Cribs.MobileFilter.SetupUI();'); ?>
