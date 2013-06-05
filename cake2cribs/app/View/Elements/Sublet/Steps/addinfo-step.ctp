<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<strong>Housemate Information</strong>
		</div>
	</div>
	<input id="Housemate_id" type="hidden">
	<div class="row-fluid">
		<div class="span6">
			<div class="row-fluid">
				<label class="span8"><strong>Estimated Housemates:</strong></label>
				<select id="Housemate_quantity" class="span3">
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
		<div class="span6">
			<div class="row-fluid">
				<label class="span7"><strong>Are They Students?:</strong></label>
				<select id="Housemate_enrolled" class="span4">
					<option></option>
					<option value="1">Yes</option>
					<option value="0">No</option>
				</select>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span4">
			<div class="row-fluid">
				<label class="span4"><strong>Type:</strong></label>
				<select id="Housemate_student_type_id" class="span8">
					<option value="0"></option>
					<option value="1">Graduate</option>
					<option value="2">Undergraduate</option>
					<option value="3">Mix</option>
				</select>
			</div>
		</div>
		<div class="span4">
			<div class="row-fluid">
				<label class="span4"><strong>Year:</strong></label>
				<select id="Housemate_year" class="span8">
					<option value="0"></option>
					<option value="Freshman">Freshman</option>
					<option value="Sophomore">Sophomore</option>
					<option value="Junior">Junior</option>
					<option value="Senior">Senior</option>
					<option value="Mix">Mix</option>
				</select>
			</div>
		</div>
		<div class="span4">
			<div class="row-fluid">
				<label class="span5"><strong>Gender:</strong></label>
				<select id="Housemate_gender_type_id" class="span6">
					<option value="0"></option>
					<option value="1">Male</option>
					<option value="2">Female</option>
					<option value="3">Mix</option>
				</select>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<label class="span2"><strong>Majors:</strong></label>
		<input id="Housemate_major" type="text" class="span10">
	</div>
	<div class="row-fluid">
		<strong>Longer Sublet Description </strong><strong class="text-info">(Optional)</strong>
	</div>
	<div class="row-fluid">
		<textarea id="Sublet_description" class="span12" rows="5"></textarea>
	</div>
</div>