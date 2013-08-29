<?php echo $this->Html->css('/less/Listing/full_page.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->script('src/FullListing.js', array('inline' => false)); ?>
<?php echo $this->element('header', array('show_filter' => false, 'show_user' => true)); ?>
<script type="text/javascript">
  var lolz = <?php echo $listing_json; ?>;
</script>
<div class="row-fluid full_page">
	<!-- Listing View side bar -->
	<div class="span3 offset1">

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
				if (array_key_exists("unit_style_description", $listing["Rental"]) && $listing["Rental"]["unit_style_description"])
					echo " | " . $listing["Rental"]["unit_style_description"];
				?>
			</div>
			<div class="row-fluid detail_table">
				<div class="span4 detail_table_cell first-child">
					<?php
					echo "<i class='big'>" . $listing["Rental"]["beds"] . "</i>&nbsp;Bed";
					if ($listing["Rental"]["beds"] > 1)
						echo "s";
					?>
				</div>
				<div class="span4 detail_table_cell">
					<?php
					echo "<i class='big'>" . $listing["Rental"]["baths"] . "</i>&nbsp;Bath";
					if ($listing["Rental"]["baths"] > 1)
						echo "s";
					?>
				</div>
				<div class="span4 detail_table_cell">
					<?php
					echo "<div class='available ";
					if (!array_key_exists("available", $listing["Rental"]) || $listing["Rental"]["available"] == null)
						echo "leased'>Maybe Avail</div>";
					else if ($listing["Rental"]["available"])
						echo "'>Available</div>";
					else
						echo "leased'>Leased</div>";
					?>
				</div>
			</div>
			<?php
			if ($this->Session->read('Auth.User.id') != 0)
			{
				if ($listing['Favorite'])
					echo '<a href="#" class="favorite_listing active" onclick="A2Cribs.FavoritesManager.DeleteFavorite(' . $listing["Listing"]["listing_id"] . ', this)"><i class="icon-heart icon-large"></i></a>';
				else 
					echo '<a href="#" class="favorite_listing" onclick="A2Cribs.FavoritesManager.AddFavorite(' . $listing["Listing"]["listing_id"] . ', this)"><i class="icon-heart icon-large"></i></a>';
			}
			else
				echo '<a href="#" class="favorite_listing" onclick="A2Cribs.UIManager.Error(\'Please log in or sign up to favorite!\')"><i class="icon-heart icon-large"></i></a>';

			?>
		</div>

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

			<div class="row-fluid detail_row">
				<div class="span12">
					<i class-"icon-map-marker"></i><?= $listing["Marker"]["street_address"] . ", " . $listing["Marker"]["city"] . ", " .$listing["Marker"]["state"] . " " . $listing["Marker"]["zip"] ?>
				</div>
			</div>
		</div>

		<div class="row-fluid contact_info">
			<div class="span12">
				<div class="row-fluid">
					<div class="span12 owner_info">
						<?
						$pic_url = "/img/head_large.jpg";
						if(array_key_exists('facebook_userid', $listing['User']) && $listing['User']['facebook_userid'] !== null)
							$pic_url = "https://graph.facebook.com/".$listing['User']['facebook_userid']."/picture?width=80&height=80";
						?>
						<img src="<?= $pic_url ?>" class="pull-left">
						<div class="owner"><?= $listing["User"]["company_name"] ?></div>
						<?= ($listing["User"]["verified"]) ? '<div class="verified">VERIFIED</div>' : '' ; ?>
					</div>
				</div>
				<div class="row-fluid hide" id="contact_message">
					<?php
					if (array_key_exists('contact_phone', $listing['Rental']) && $listing["Rental"]["contact_phone"] != null)
					{ ?>
						<div class="row-fluid phone">
							Phone Number: <?= $listing["Rental"]["contact_phone"] ?>
						</div>
					<?php } else {?>
						<div class="row-fluid phone">
							Phone Number: Not Available
						</div>
					<? } ?>
					<?php if ($email_exists){ ?>
					<div class="row-fluid">
						<textarea id="message_area" class="span12" rows="3"></textarea>
					</div>
					<div class="row-fluid">
						<button id="message_cancel" class="btn span5">Cancel</button>
						<button id="message_send" class="btn span7" type="button" data-loading-text="Sending...">Send Message</button>
					</div>
					<?php } ?>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<button class="btn" id="contact_owner" emailExists='<?php echo $email_exists; ?>'>CONTACT RENTAL OWNER</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="span7 middle_content tabbable">
		<div class="navbar option_panel">
			<div class="navbar-inner">
				<ul class="nav">
					<li><a href="/map">Return to Map</a></li>
					<li class="active"><a href="#photo_content" data-toggle="tab">Photos</a></li>
					<li><a href="#details_content" data-toggle="tab">Details</a></li>
					<li><a href="#amenities_content" data-toggle="tab">Amenities</a></li>
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
					if (array_key_exists('primary_image', $listing) && array_key_exists('Image', $listing)) {
						$primary_url = $listing["Image"][$listing["primary_image"]]["image_path"];
					}
					?>
					<div id="main_photo" style="background-image:url(/<?= $primary_url ?>)"></div>
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
								$included = array('electric', 'air', 'gas', 'water', 'cable', 'internet', 'trash');
								$descriptions = array('Electricity', 'A/C', 'Gas', 'Water', 'Cable', 'Internet', 'Trash');

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
		</div>
		
	</div>
</div>

<?php 
	$this->Js->buffer('
		A2Cribs.FullListing.SetupUI();
		A2Cribs.FullListing.Directive(' . $directive . ');
	');
?>