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