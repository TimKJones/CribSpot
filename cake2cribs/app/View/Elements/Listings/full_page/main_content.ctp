<div class="span7 middle_content tabbable">
	<?= $this->element('Listings/full_page/navbar'); ?>
	<div class="tab-content">
		<?= $this->element('Listings/full_page/photo_tab'); ?>
		<?= $this->element('Listings/full_page/details_tab', array('listing' => $listing)); ?>
		<?php 
		if (array_key_exists('Rental', $listing))
			echo $this->element('Listings/full_page/amenities_tab'); 
		?>
		
		<?= $this->element('Listings/schedule_tour'); ?>
	</div>
</div>