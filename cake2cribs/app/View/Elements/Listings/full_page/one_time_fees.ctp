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