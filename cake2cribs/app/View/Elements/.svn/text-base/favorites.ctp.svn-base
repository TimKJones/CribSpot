<div id="favoritesBar">
	<div id="favoriteActionsButton"></div>
	<div id="hideFavoritesDiv"></div>
	<div id="personalFavoritesList">
		<div id="noFavorites"></div>
	</div>


	<!-- Favorite Template -->
	<div id="favoriteTemplate" style="display:none;">
		<div class="favoriteDiv"> <!-- ID ==> favoriteDiv{listingID} -->
			<div class="favoritesAddress">Evanville</div> <!-- Address or alt name in bold -->
			<div class="removeButton"></div> <!-- Onclick delete favorite -->
			<div class="optionGrid">
				<div id="price" title="Rent for Entire Unit">$10</div>
				<div id="beds" title="Bedrooms">2 Beds</div>
				<div id="furnished" title="Furnished"><div class="smallIcon"></div></div>
				<div id="parking" title="Parking"><div class="smallIcon"></div></div>
				<div id="ac" title="Air Conditioning"><div class="smallIcon"></div></div>
				<div id="payMonth" title="Lease Period">Fall</div>
				<div id="aptType" title="Unit Type">Apt</div>
				<div id="baths" title="Bathrooms">2 Baths</div>
				<div id="electric" title="Electricity"><div class="smallIcon"></div></div>
				<div id="heat" title="Heating"><div class="smallIcon"></div></div>
				<div id="water" title="Water"><div class="smallIcon"></div></div>
				<div id="contact" title="Contact Information"><a target="_blank" href="">Contact</a></div>
			</div>
		</div>
	</div>

</div>
<div id="favoritesTab">
	<div id="numFavorites">0</div>
</div>
<?php
	$this->Js->get('#favoritesTab');
	$this->Js->event('click', 
		'$("#favoritesTab").hide("slide", { direction: "left" }, 500);
		$("#favoritesBar").show("slide", { direction: "left" }, 500);'
	);

	$this->Js->get('#hideFavoritesDiv');
	$this->Js->event('click', 
		'$("#favoritesTab").show("slide", { direction: "left" }, 500);
		$("#favoritesBar").hide("slide", { direction: "left" }, 500);'
	);
?>