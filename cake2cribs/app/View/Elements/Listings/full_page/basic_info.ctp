<div class="row-fluid basic_info">
	<div class="name">
		<?php
		if (strlen($listing["Marker"]["alternate_name"]) != 0)
			echo $listing["Marker"]["alternate_name"];
		else
			echo $listing["Marker"]["street_address"];
		?>
	</div>
	<div class="building_type">
		<?php
		if (array_key_exists("building_type_id", $listing["Marker"]))
			echo $listing["Marker"]["building_type_id"];
		if (array_key_exists('Rental', $listing) &&
			array_key_exists("unit_style_description", $listing["Rental"]) && 
			$listing["Rental"]["unit_style_description"])
			echo " | " . $listing["Rental"]["unit_style_description"];
		?>
	</div>
	<div class="row-fluid detail_table">
		<div class="span4 detail_table_cell first-child">
			<?php
			echo "<i class='big'>" . $listing[$listing_type]["beds"] . "</i>&nbsp;Bed";
			if ($listing[$listing_type]["beds"] > 1)
				echo "s";
			?>
		</div>
		<div class="span4 detail_table_cell">
			<?php
			echo "<i class='big'>" . $listing[$listing_type]["baths"] . "</i>&nbsp;Bath";
			if ($listing[$listing_type]["baths"] > 1)
				echo "s";
			?>
		</div>
		<div class="span4 detail_table_cell">
			<?php
			echo "<div class='available ";
			if (!array_key_exists("available", $listing["Listing"]) || $listing["Listing"]["available"] === null)
				echo "unknown'>Available?</div>";
			else if ($listing["Listing"]["available"])
				echo "'>Available</div>";
			else
				echo "leased'>Leased</div>";
			?>
		</div>
	</div>
	<a href="#" class="favorite_listing" data-listing-id="<?= $listing["Listing"]["listing_id"]; ?>" ><i class="icon-heart icon-large"></i></a>
</div>