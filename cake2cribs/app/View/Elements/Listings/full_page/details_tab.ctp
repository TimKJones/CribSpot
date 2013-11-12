<div id="details_content" class="tab-pane">
<?php 
if (array_key_exists('Rental', $listing))
	echo $this->element('Listings/full_page/included_amenities');
?>
	<div class="row-fluid">
		<div class="span4">
<?php
		if (array_key_exists('Rental', $listing)){
			echo $this->element('Listings/full_page/monthly_fees');
			echo $this->element('Listings/full_page/one_time_fees');
		} else if (array_key_exists('Sublet', $listing)){
			echo $this->element('Listings/full_page/sublets_included');
		}
?>
		</div>
		<div class="span8">
			<div class="row-fluid info_label">About This Crib:</div>
			<div class="row-fluid info_box">
				<div class="span12">
					<?= $listing[$listing_type]["description"] ?>
				</div>
			</div>
		</div>
	</div>
</div>