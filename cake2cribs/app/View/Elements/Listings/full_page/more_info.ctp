<div class="row-fluid more_info">
			<div class="row-fluid detail_table">
				<div class="span6 detail_table_cell first-child"><i class="rent"><?= $listing["Rental"]["rent"] ?></i><br>Monthly Rent</div>
				<div class="span6 detail_table_cell">
					<i class="big">
						<?= $listing["Rental"]["deposit_amount"] ?>
					</i>
					<br>Deposit
				</div>
			</div>
			<div class="row-fluid detail_row first-child">
				<div class="span12">
					<i class="icon-calendar"></i>&nbsp;
					<?php
					if (array_key_exists('start_date', $listing['Rental']) && $listing['Rental']['start_date'] != null)
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
					?>
				</div>
			</div>


			<?php
			if ($listing["Rental"]["square_feet"] != null) {
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
					<img src="/img/full_page/icon/electric<?= (intval($listing["Rental"]["electric"]) > 0) ? "" : "_not_included" ; ?>.png">
					<img src="/img/full_page/icon/gas<?= (!$listing['Rental']['gas']) ? "_not_included" : "" ; ?>.png">
					<img src="/img/full_page/icon/water<?= (!$listing['Rental']['water']) ? "_not_included" : "" ; ?>.png">
					<?php
					if (strcmp($listing["Rental"]["parking_type"], "No") == 0 || strcmp($listing["Rental"]["parking_type"], "-") == 0)
						echo '<img src="/img/full_page/icon/parking_not_included.png">';
					else 
						echo '<img src="/img/full_page/icon/parking.png">';

					if (strcmp($listing["Rental"]["furnished_type"], "No") == 0 || strcmp($listing["Rental"]["furnished_type"], "-") == 0)
						echo '<img src="/img/full_page/icon/furnished_not_included.png">';
					else 
						echo '<img src="/img/full_page/icon/furnished.png">';
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