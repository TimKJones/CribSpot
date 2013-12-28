<div class="navbar option_panel">
	<div class="navbar-inner">
		<ul class="nav">
			<li><a href="/map">Return to Map</a></li>
			<li class="active"><a href="#photo_content" data-toggle="tab">Photos</a></li>
			<li><a href="#details_content" data-toggle="tab">Details</a></li>
<?php if (array_key_exists('Rental', $listing)){ ?>
			<li><a href="#amenities_content" data-toggle="tab">Amenities</a></li>
<?php } ?>
			<?php if ($listing['Listing']['available'] === true){ ?>
				<li><a id="scheduling_tour_tab" class="show_scheduling" href="#schedule_tour">Request My Tour</a></li>
			<?php } ?>
		</ul>
		<ul class="nav pull-right share_buttons">
			<li class="disabled"><a href="">Share This Listing:</a></li>
			<li><a onclick="A2Cribs.ShareManager.CopyListingUrl(<?= $listing["Listing"]["listing_id"] . ",'" . $listing["Marker"]["street_address"] . "','" . $listing["Marker"]["city"] . "','" . $listing["Marker"]["state"] . "'," . $listing["Marker"]["zip"] ?>)" href="#"><i class="icon-link"></i></a></li>
			<li><a onclick="A2Cribs.ShareManager.ShareListingOnFacebook(<?= $listing["Listing"]["listing_id"] . ",'" . $listing["Marker"]["street_address"] . "','" . $listing["Marker"]["city"] . "','" . $listing["Marker"]["state"] . "'," . $listing["Marker"]["zip"] ?>)" href="#"><i class="icon-facebook"></i></a></li>
			<li><a id="twitter_link" onclick="A2Cribs.ShareManager.ShareListingOnTwitter(<?= $listing["Listing"]["listing_id"] . ",'" . $listing["Marker"]["street_address"] . "','" . $listing["Marker"]["city"] . "','" . $listing["Marker"]["state"] . "'," . $listing["Marker"]["zip"] ?>)" href="#"><i class="icon-twitter"></i></a></li>
		</ul>
	</div>
</div>
