<div id="details_content" class="tab-pane">
	<div class="row-fluid">
		<div class="span12 info_label">
			Included:
		</div>
	</div>
<?php if (array_key_exists('Rental', $listing)) { ?>
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
<?php } ?>
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