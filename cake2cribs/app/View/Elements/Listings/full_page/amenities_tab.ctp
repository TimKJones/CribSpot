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