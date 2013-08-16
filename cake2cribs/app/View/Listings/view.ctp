<?php echo $this->Html->css('/less/Listing/full_page.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->script('src/FullListing.js', array('inline' => false)); ?>
<?php //secho $this->element('header', array('show_filter' => false, 'show_user' => true)); ?>
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
				echo $listing["Marker"]["building_type_id"] . " | " . $listing["Rental"]["unit_style_description"];
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
					echo "<i class='big'>" . $listing["Rental"]["baths"] . "</i>&nbsp;Bed";
					if ($listing["Rental"]["baths"] > 1)
						echo "s";
					?>
				</div>
				<div class="span4 detail_table_cell">
					<?php
					echo "<div class='available ";
					if ($listing["Rental"]["available"])
						echo "'>Available</div>";
					else
						echo "leased'>Leased</div>";
					?>
				</div>
			</div>
			<div class="favorite_listing"><i class="icon-heart icon-large"></i></div>
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
						$months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
						list($year, $month, $day, $time) = split('[ /.-]', $listing["Rental"]["start_date"]);
						echo $months[intval($month) - 1] . " " . intval($day) . ", " . $year;
					?>
					 | 8 months
				</div>
			</div>


			<?php
			if ($listing["Rental"]["square_feet"] != null) {
			?>
			<div class="row-fluid detail_row">
				<div class="span12">
					<i class="icon-home"></i>&nbsp;<?= $listing["Rental"]["square_feet"] ?> SQ FT | 6 Other Layouts
				</div>
			</div>
			<?php } ?>

			<?php
			if ($listing["Rental"]["smoking"] != null || $listing["Rental"]["pets_type"]) { ?>
			<div class="row-fluid detail_row">
				<div class="span12">
					<i class="icon-ban-circle"></i>&nbsp;Pets Allowed | Smoking Allowed
				</div>
			</div>
			<?php } ?>

			<div class="row-fluid detail_row">
				<div class="span12">
					What's Included: lol
				</div>
			</div>

			<div class="row-fluid detail_row">
				<div class="span12">
					<i class-"icon-map-marker"></i><?= $listing["Marker"]["street_address"] . ", " . $listing["Marker"]["city"] . ", " .$listing["Marker"]["state"] . " " . $listing["Marker"]["zip"] ?>
				</div>
			</div>
		</div>

		<div class="row-fluid contact_info">
			<div class="row-fluid">
				<div class="span12 owner_info">
					<img src="" class="pull-left">
					<div class="pull-left owner"><?= $listing["User"]["company_name"] ?></div>
				</div>
			</div>
			<div class="row-fluid hide" id="contact_message">
				<div class="row-fluid">
					<textarea id="message_area" class="span12" rows="3"></textarea>
				</div>
				<div class="row-fluid">
					<button id="message_cancel" class="span5">Cancel</button>
					<button id="message_send" class="btn btn-primary span7" type="button" data-loading-text="Sending...">Send Message</button>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<button id="contact_owner">CONTACT RENTAL OWNER</button>
				</div>
			</div>
		</div>
	</div>

	<div class="span7 middle_content tabbable">
		<div class="navbar option_panel">
			<div class="navbar-inner">
				<ul class="nav">
					<li><a href="#">Return to Map</a></li>
					<li class="active"><a href="#photo_content" data-toggle="tab">Photos</a></li>
					<li><a href="#details_content" data-toggle="tab">Details</a></li>
					<li><a href="#amenities_content" data-toggle="tab">Amenities</a></li>
				</ul>
				<ul class="nav pull-right share_buttons">
					<li class="disabled"><a href="">Share This Listing:</a></li>
					<li><a href=""><i class="icon-link"></i></a></li>
					<li><a href=""><i class="icon-facebook"></i></a></li>
					<li><a href=""><i class="icon-twitter"></i></a></li>
				</ul>
			</div>
		</div>
		<div class="tab-content">
			<div id="photo_content" class="tab-pane active">
				<div class="large_image_container">
					<div id="main_photo" style="background-image:url(/<?= $listing["Image"][$listing["primary_image"]]["image_path"] ?>)"></div>
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
						<ul>
							<li>Electricity</li>
							<li>Parking</li>
							<li>Internet</li>
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
								<td>?</td>
								<td>?</td>
								<td>?</td>
								<td>?</td>
								<td>?</td>
								<td>?</td>
								<td>?</td>
								<td>?</td>
								<td>?</td>
								<td>?</td>
								<td>?</td>
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
	');
?>