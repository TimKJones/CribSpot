<div class="row-fluid">
	<div class="span12 info_label">
		Included:
	</div>
</div>
<div class="row-fluid">
	<div class="span12 info_box">
		<table>
			<tr>
				<td>Air Conditioning</td>
				<td><?= $listing["Sublet"]["air"] ?></td>
			</tr>
			<tr>
				<td>Furnished</td>
				<td><?= $listing["Sublet"]["furnished_type"] ?></td>
			</tr>
			<tr>
				<td>Washer/Dryer</td>
				<td><?= $listing["Sublet"]["washer_dryer"] ?></td>
			</tr>
			<tr>
				<td>Private Bathroom</td>
				<td><?= $listing["Sublet"]["bathroom_type"] ?></td>
			</tr>
			<tr><td><br></td></tr>
			<tr>
				<td>Parking</td>
				<td><?= $listing["Sublet"]["parking_description"] ?></td>
			</tr>
			<tr><td><br></td></tr>
			<tr>
				<td>Utilities Included</td>
				<td><?= $listing["Sublet"]["utilities_description"] ?></td>
			</tr>
		</table>
	</div>
</div>