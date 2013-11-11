<?php echo $this->Html->css('/less/Listing/full_page.less?v=4','stylesheet/less', array('inline' => false)); ?>
<?php 
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	echo $this->Html->script('src/FullListing.js', array('inline' => false));
}
	$name = "";
	if (strlen($listing["Marker"]["alternate_name"]) != 0)
		$name = $listing["Marker"]["alternate_name"] . " - ";

	$this->set('title_for_layout', $name . $listing["Marker"]["street_address"] . ", " . $listing["Marker"]["city"] . ", " .$listing["Marker"]["state"] . " " . $listing["Marker"]["zip"] . " - Cribspot");

	$this->Html->meta('keywords', 
			$listing["Marker"]["alternate_name"] . ", " . $listing["Marker"]["street_address"] . ", off campus housing, student housing, college rental, college sublet, college parking, college sublease", array('inline' => false)
		);

	$this->Html->meta('description', $listing["Rental"]["description"], array('inline' => false));

	echo $this->element('SEO/places_rich_snippet', array('latitude' => $listing["Marker"]["latitude"], 'longitude' => $listing["Marker"]["longitude"]));

?>
<?php echo $this->element('header', array('show_filter' => false, 'show_user' => true)); ?>
<input id="listing-data" type="hidden" data-listing-id="<?= $listing["Listing"]["listing_id"]; ?>">

<div class="row-fluid full_page">
	<!-- Listing View side bar -->
	<div class="span3 offset1">
		<?= $this->element('Listings/full_page/basic_info'); ?>
		<?= $this->element('Listings/full_page/more_info'); ?>
		<?= $this->element('Listings/full_page/contact_info'); ?>
	</div>

	<div class="span7 middle_content tabbable">
		<div class="navbar option_panel">
			<div class="navbar-inner">
				<ul class="nav">
					<li><a href="/map">Return to Map</a></li>
					<li class="active"><a href="#photo_content" data-toggle="tab">Photos</a></li>
					<li><a href="#details_content" data-toggle="tab">Details</a></li>
					<li><a href="#amenities_content" data-toggle="tab">Amenities</a></li>
					<?php if ($listing['Listing']['scheduling'] === true && $listing['Listing']['available'] === true){ ?>
						<li><a id="scheduling_tour_tab" class="show_scheduling" href="#schedule_tour">Schedule My Tour</a></li>
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
		<div class="tab-content">
			<div id="photo_content" class="tab-pane active">
				<div class="large_image_container">
					<?php
					$primary_url = 'img/full_page/no_photo.jpg';
					$has_primary_photo = false;
					if (array_key_exists('primary_image', $listing) && array_key_exists('Image', $listing)) {
						$primary_url = $listing["Image"][$listing["primary_image"]]["image_path"];
						$has_primary_photo = true;
					}
					?>
					<div id="main_photo" class="<?= ($has_primary_photo) ? '' : 'no_photo' ; ?>" style="background-image:url(/<?= $primary_url ?>)"></div>
				</div>
				<div class="image_footer">
					<div class="page_left"><i class="icon-chevron-left icon-large"></i></div>
					<div class="image_preview_container">
						<?php
						$length = count($listing["Image"]);
						for ($i=0; $i < $length; $i++) { 
							if ($listing["primary_image"] == $i)
								echo '<div class="image_preview active" style="background-image:url(/' . $listing["Image"][$i]["image_path"] . ')"></div>';
							else
								echo '<div class="image_preview" style="background-image:url(/' . $listing["Image"][$i]["image_path"] . ')"></div>';
						}
						?>
					</div>
					<div class="page_right"><i class="icon-chevron-right icon-large"></i></div>
				</div>
			</div>
			<div id="details_content" class="tab-pane">
				<div class="row-fluid">
					<div class="span12 info_label">
						Included:
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<ul class="large_included_list">
							<?php
								$included = array('electric', 'gas', 'water', 'cable', 'internet', 'trash');
								$descriptions = array('Electricity', 'Gas', 'Water', 'Cable', 'Internet', 'Trash');

								$length = count($included);

								for ($i=0; $i < $length; $i++) { 
									if (intval($listing["Rental"][$included[$i]]) > 0)
										echo '<li><img src="/img/full_page/large_' . $included[$i] . '.png">' . $descriptions[$i] . '</li>';
									//else if (strcmp($listing["Rental"][$included[$i]], "Yes") == 0)
									//	echo '<li><img src="/img/listings/large_' . $included[$i] . '.png">' . $descriptions[$i] . '</li>';
									else
										echo '<li class="disabled"><img src="/img/full_page/large_' . $included[$i] . '_not_included.png">' . $descriptions[$i] . '</li>';
								}
							?>
						</ul>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span4">
						<div class="row-fluid info_label">
							Monthly Fees:
						</div>
						<div class="row-fluid info_box">
							<table>
								<tr>
									<td>Monthly Rent</td>
									<td><?= $listing["Rental"]["rent"] ?></td>
								</tr>
								<tr>
									<td>Additional Occupants</td>
									<td><?= $listing["Rental"]["extra_occupant_amount"] ?></td>
								</tr>
								<tr>
									<td>Parking</td>
									<td><?= $listing["Rental"]["parking_amount"] ?></td>
								</tr>
								<tr>
									<td>Furniture</td>
									<td><?= $listing["Rental"]["furniture_amount"] ?></td>
								</tr>
								<tr>
									<td>Amenity</td>
									<td><?= $listing["Rental"]["amenity_amount"] ?></td>
								</tr>
								<tr>
									<td>Upper Floor</td>
									<td><?= $listing["Rental"]["upper_floor_amount"] ?></td>
								</tr>
								<?php
								/*<tr> We currently don't have this field
									<td>Other Fees</td>
									<td><?= $listing["Rental"]["rent"] ? "$" . $listing["Rental"]["extra_occupant_amount"] : "-" ?></td>
								</tr>*/
								?>
								<tr>
									<td>Total Fees</td>
									<td><?= $listing["Rental"]["total_fees"] ?></td>
								</tr>
							</table>
						</div>
						<div class="row-fluid info_label">
							One-Time Fees:
						</div>
						<div class="row-fluid info_box">
							<table>
								<tr>
									<td>Security Deposit</td>
									<td><?= $listing["Rental"]["deposit_amount"] ?></td>
								</tr>
								<tr>
									<td>Administrative Fee</td>
									<td><?= $listing["Rental"]["admin_amount"] ?></td>
								</tr>
								<?php
								/* We currently don't have this field
								<tr>
									<td>Application</td>
									<td>-</td>
								</tr> */
								?>
							</table>
						</div>
					</div>
					<div class="span8">
						<div class="row-fluid info_label">About This Crib:</div>
						<div class="row-fluid info_box">
							<div class="span12">
								<?= $listing["Rental"]["description"] ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="amenities_content" class="tab-pane">
				<div class="row-fluid">
					<div class="span12 info_label">
						Included:
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12 amenities_table">
						<table>
							<tr>
								<?php
								$fields = array('pool', 'hot_tub', 'fitness_center', 'game_room', 'front_desk', 'security_system', 'tanning_beds', 'study_lounge', 'patio_deck', 'yard_space', 'elevator');
								foreach ($fields as $field) {
									if ($listing["Rental"][$field] === null)
										echo "<td>?</td>";
									elseif ($listing["Rental"][$field])
										echo "<td><img src='/img/full_page/amenities_check.png' ></td>";
									else
										echo "<td><img src='/img/full_page/amenities_no_check.png' ></td>";
								}
								?>
							</tr>
							<tr>
								<td>Pool</td>
								<td>Hot Tub</td>
								<td>Fitness Center</td>
								<td>Game Room</td>
								<td>Front Desk</td>
								<td>Security System</td>
								<td>Tanning Beds</td>
								<td>Study Lounge</td>
								<td>Patio or Deck</td>
								<td>Yard Space</td>
								<td>Elevator</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span6">
						<div class="row-fluid">
							<div class="span12 info_label">
								In-Unit Amenities:
							</div>
						</div>
						<div class="row-fluid">
							<div class="span12 info_box">
								<table>
									<tr>
										<td>Air Conditioning</td>
										<td><?= $listing["Rental"]["air"] ?></td>
									</tr>
									<tr>
										<td>Furnished</td>
										<td><?= $listing["Rental"]["furnished_type"] ?></td>
									</tr>
									<tr>
										<td>Washer/Dryer</td>
										<td><?= $listing["Rental"]["washer_dryer"] ?></td>
									</tr>
									<tr>
										<td>Television</td>
										<td><?= $listing["Rental"]["tv"] ?></td>
									</tr>
									<tr>
										<td>Balcony</td>
										<td><?= $listing["Rental"]["balcony"] ?></td>
									</tr>
									<tr>
										<td>Fridge</td>
										<td><?= $listing["Rental"]["fridge"] ?></td>
									</tr>
									<tr>
										<td>Storage</td>
										<td><?= $listing["Rental"]["storage"] ?></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div class="span6">
						<div class="row-fluid">
							<div class="span12 info_label">
								Other Stuff:
							</div>
						</div>
						<div class="row-fluid">
							<div class="span12 info_box">
								<table>
									<tr>
										<td>Street Parking Available</td>
										<td><?= $listing["Rental"]["street_parking"] ?></td>
									</tr>
									<tr>
										<td>Private Parking</td>
										<td><?= $listing["Rental"]["parking_type"] ?></td>
									</tr>
									<tr>
										<td>Parking Spots</td>
										<td><?= $listing["Rental"]["parking_spots"] ?></td>
									</tr>
									<tr>
										<td>Pets</td>
										<td><?= $listing["Rental"]["pets_type"] ?></td>
									</tr>
									<tr>
										<td>Smoking</td>
										<td><?= $listing["Rental"]["smoking"] ?></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?= $this->element('Listings/schedule_tour'); ?>
		</div>
		
	</div>
</div>

<?php 
	$this->Js->buffer('
		A2Cribs.FullListing.SetupUI(' . $listing["Listing"]["listing_id"] . ');
		A2Cribs.FullListing.Directive(' . $directive . ');
	');
?>