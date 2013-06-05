<div class="container-fluid">
	<div class="row-fluid">
		<label class="span3"><strong>Available From:</strong></label>
		<div class="span6">
			<div class="row-fluid control-group">
				<input id="Sublet_date_begin" type="text" class="span5 required date_field" placeholder = 'Start Date'>
				<div class="span2" id="divider">-</div>
				<input id="Sublet_date_end" type="text" class="span5 required date_field" placeholder = 'End Date'>							
			</div>
		</div>
		<div class="span3">
			<div class="row-fluid">
				<label class="span9"><strong>Flexible Dates:</strong></label>
				<input id="Sublet_flexible_dates" type="checkbox">
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<div class="row-fluid control-group">
				<label class="span8"><strong>Bedrooms:</strong></label>
				<select id="Sublet_number_bedrooms" class="span4 required">
					<option></option>
					<option>0</option>
					<option>1</option>
					<option>2</option>
					<option>3</option>
					<option>4</option>
					<option>5</option>
					<option>6</option>
					<option>7</option>
					<option>8</option>
					<option>9</option>
					<option>10</option>
					<option>11</option>
					<option>12</option>
				</select>
			</div>
		</div>
		<div class="span3">
			<div class="row-fluid control-group">
				<label class="span6"><strong>Price</strong><small> /Bed</small><strong>:</strong></label>
				<div class="input-prepend span6 required">
					<span class="add-on span3">$</span>
					<input class="span9" id="Sublet_price_per_bedroom" type="text">
					<input id="Sublet_payment_type_id" type="hidden" value="1">
				</div>
			</div>
		</div>
		<div class="span6">
			<div class="row-fluid">
				<label class="span4"><strong>Per Month</strong></label>
			</div>
		</div>
	</div>
	<div class="row-fluid control-group">
		<div class="span3">
			<strong>Short Description:</strong>
			<p class="text-info"><small><strong>160 Character Max</strong></small></p>
			<p class="text-error"><small><strong><i id="desc-char-left">160</i> Characters Left</strong></small></p>
		</div>
		<textarea id="Sublet_short_description" class="span9 required" rows="3"></textarea>
	</div>
	<div class="row-fluid">
		<div class="span6"><strong>Additional Details </strong></div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<div class="row-fluid control-group">
				<label class="span4"><strong>Furnished:</strong></label>
				<select id="Sublet_furnished_type_id" class="span4 required">
					<option></option>
					<option value="1">Fully</option>
					<option value="2">Partially</option>
					<option value="3">No</option>
				</select>
			</div>
		</div>
		<div class="span6">
			<div class="row-fluid control-group">
				<label class="span4"><strong>Deposit:</strong></label>
				<div class="input-prepend span4 pull-left">
					<span class="add-on span1">$</span>
					<input class="span10" id="Sublet_deposit_amount" type="text">
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<div class="row-fluid control-group">
				<label class="span4"><strong>Parking:</strong></label>
				<select id="Sublet_parking" class="span3 required">
					<option></option>
					<option value="1">Yes</option>
					<option value="0">No</option>
				</select>
			</div>
		</div>
		<div class="span6">
			<div class="row-fluid control-group">
				<label class="span4"><strong>Utilities:</strong></label>
				<select id="Sublet_utility_type_id" class="span5 required">
					<option></option>
					<option value="1">Included</option>
					<option value="2">Monthly Fee</option>
					<option value="3">As Used</option>
				</select>
				<div class="input-prepend span3 pull-right">
					<span class="add-on span1">$</span>
					<input class="span8" id="Sublet_utility_cost" value="0" type="text">
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<div class="row-fluid control-group">
				<label class="span4"><strong>Bathroom:</strong></label>
				<select id="Sublet_bathroom_type_id" class="span4 required">
					<option></option>
					<option value="1">Private</option>
					<option value="2">Shared</option>
				</select>
			</div>
		</div>
		<div class="span6">
			<div class="row-fluid">
				<label class="span4"><strong>Other Fees:</strong></label>
				<input id="Sublet_additional_fees_description" type="text" class="span5" placeholder="Description">
				<div class="input-prepend span3 pull-right">
					<span class="add-on span1">$</span>
					<input class="span8" id="Sublet_additional_fees_amount" type="text">
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<div class="row-fluid">
				<label class="span4"><strong>A/C:</strong></label>
				<select id="Sublet_ac" class="span3">
					<option></option>
					<option value="1">Yes</option>
					<option value="0">No</option>
				</select>
			</div>
		</div>
	</div>
</div>