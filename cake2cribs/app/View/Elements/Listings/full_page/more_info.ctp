<div class="row-fluid more_info">
	<div class="row-fluid detail_table">

<?php if (array_key_exists('Rental', $listing)) { ?> 
		<div class="span6 detail_table_cell first-child"><i class="rent"><?= $listing[$listing_type]["rent"] ?></i><br>Monthly Rent</div>
<?php } else if (array_key_exists('Sublet', $listing)) { ?>
		<div class="span12 detail_table_cell first-child"><i class="rent"><?= $listing[$listing_type]["rent"] ?></i><br>Monthly Rent</div>
<?php } ?>
<?php if (array_key_exists('Rental', $listing)) { ?>
		<div class="span6 detail_table_cell">
			<i class="big">
				<?= $listing["Rental"]["deposit_amount"] ?>
			</i>
			<br>Deposit
		</div>
<?php } ?>
		<div class="row-fluid detail_row first-child">
			<div class="span12">
				<i class="icon-calendar"></i>&nbsp;
<?php

				if (array_key_exists('Rental', $listing)){
					if (array_key_exists('start_date', $listing[$listing_type]) && $listing['Rental']['start_date'] != null)
					{
						$months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
						list($year, $month, $day, $time) = split('[ /.-]', $listing["Rental"]["start_date"]);
						echo $months[intval($month) - 1] . " " . intval($day) . ", " . $year;
					}
					else
						echo "Unknown Start Date";

					if (array_key_exists('lease_length', $listing['Rental']) && $listing['Rental']['lease_length'] != null)
					{
						echo " | " . $listing['Rental']['lease_length'] . "month";
						if (intval($listing['Rental']['lease_length']) > 1)
							echo "s";
					}
				} else if (array_key_exists('Sublet', $listing)){
					echo $listing['Sublet']['formatted_date_range'];
				}
?>
			</div>
		</div>


		<?php
		if (array_key_exists('Rental', $listing) && $listing["Rental"]["square_feet"] != null) {
		?>
		<div class="row-fluid detail_row">
			<div class="span12">
				<i class="icon-home"></i>&nbsp;<?= $listing["Rental"]["square_feet"] ?> SQ FT
			</div>
		</div>
		<?php } ?>

		<div class="row-fluid detail_row">
			<div class="span12 included_mini">
				What's Included:
				<?php if (array_key_exists('Rental', $listing)) { ?>
				<img src="/img/full_page/icon/electric<?= (intval($listing["Rental"]["electric"]) > 0) ? "" : "_not_included" ; ?>.png">
				<img src="/img/full_page/icon/gas<?= (!$listing['Rental']['gas']) ? "_not_included" : "" ; ?>.png">
				<img src="/img/full_page/icon/water<?= (!$listing['Rental']['water']) ? "_not_included" : "" ; ?>.png">
				<?php } ?>
				<?php
				if (array_key_exists('Rental', $listing)){
					if (strcmp($listing["Rental"]["parking_type"], "No") == 0 || strcmp($listing["Rental"]["parking_type"], "-") == 0)
						echo '<img src="/img/full_page/icon/parking_not_included.png">';
					else 
						echo '<img src="/img/full_page/icon/parking.png">';
				} else if (array_key_exists('Sublet', $listing)){
					if (!$listing["Sublet"]["parking_available"])
						echo '<img src="/img/full_page/icon/parking_not_included.png">';
					else 
						echo '<img src="/img/full_page/icon/parking.png">';
				}

				if (array_key_exists('Rental', $listing)){
					if (strcmp($listing[$listing_type]["furnished_type"], "No") == 0 || strcmp($listing[$listing_type]["furnished_type"], "-") == 0)
						echo '<img src="/img/full_page/icon/furnished_not_included.png">';
					else 
						echo '<img src="/img/full_page/icon/furnished.png">';
				} else if (array_key_exists('Sublet', $listing)){
					if (!$listing["Sublet"]["furnished_type"])
						echo '<img src="/img/full_page/icon/furnished_not_included.png">';
					else 
						echo '<img src="/img/full_page/icon/furnished.png">';
				}
				?>
			</div>
		</div>

		<div class="row-fluid detail_row" itemscope itemtype="http://schema.org/Residence">
			<div class="span12" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
				<i class-"icon-map-marker"></i>
				<span itemprop="streetAddress"><?= $listing["Marker"]["street_address"]; ?></span>,
				<span itemprop="addressLocality"><?= $listing["Marker"]["city"]; ?></span>,
				<span itemprop="addressRegion"><?= $listing["Marker"]["state"] ?></span> <?= $listing["Marker"]["zip"] ?>
			</div>
		</div>
	</div>
</div>