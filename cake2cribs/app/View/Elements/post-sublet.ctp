<?= $this->Html->css('/less/post-sublet.less?','stylesheet/less', array('inline' => false)); ?>
<?= $this->Html->css('/less/checkbox.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('listing-popup-verifications'); ?>

<div class="listing-popup modal container-fluid">
	<div id="sublet-id" class="hide"></div>
	<div class="modal-header">
		<i class="sublet-name title">Post Your Sublet</i>
		<div id="modal-close-button" class="close" data-dismiss="modal"></div>
	</div>
	<div>
		<div class="modal-body step" id="address-step">
			<form class="form-horizontal">
				<div class="control-group">
					<label class="control-label" for="inputSchool"><strong>College:</strong></label>
					<div class="controls">
						<input type="text" id="inputSchool" class="span2" placeholder="lol">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="inputName"><strong>Building Name:</strong></label>
					<div class="controls">
						<input type="text" id="inputName" class="span2" placeholder="lol">
					</div>
				</div>
				<div class="control-group">					
					<label class="control-label" for="inputAddress"><strong>Street Address:</strong></label>
					<div class="controls">
						<input type="text" id="inputAddress" class="span2" placeholder="lol">
						<button class="btn btn-primary" id="placeOnMap"><i class="icon-map-marker"></i> Place on Map</button>
					</div>

				</div>
				<div class="control-group">
					<label class="control-label" for="inputType"><strong>Type:</strong></label>
					<div class="controls">
						<select id="inputType" class="span2">
							<option value="">House</option>
							<option value="">Apartment</option>
							<option value="">Duplex</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="inputUnit"><strong>Unit Number:</strong></label>
					<div class="controls">
						<input type="text" id="inputUnit" class="span1" placeholder="lol">
					</div>
				</div>
			</form>
			<div class="modal-footer">
				<button class="btn btn-inverse pull-right next-btn">Next</button>
			</div>
		</div>
		<div class="modal-body step" id="info-step">
			<form class="form-inline" id="top-form">
				<div>
					<label for = 'start_date'><strong>Available From:</strong></label>
					<div class="input-append">
						<input id = 'SubletDateBegin' type="text" class="input-small" name='start_date' placeholder="Start Date">
						<span class="add-on"><i class="icon-calendar"></i></span>
	            	</div>
	            	-
	            	<div class="input-append">
						<input id = 'SubletDateEnd' type="text" class="input-small" placeholder="End Date">
						<span class="add-on"><i class="icon-calendar"></i></span>
	            	</div>

  					<label class="checkbox">
    					<strong>Flexible Dates</strong><input id = 'SubletFlexibleDates' type="checkbox"> 
  					</label>
	            </div>
	            <br>
				<div>
					<label>
						<strong>Bedrooms:</strong>
						<select id="SubletNumberBedrooms" class="input-mini">
							<option value="">1</option>
							<option value="">2</option>
							<option value="">3</option>
						</select>
					</label>
					<label><strong>Price </strong><small><b>/Bed</b></small><strong>:</strong></label>
					<div class="input-prepend">
						<span class="add-on">$</span>
						<input class="input-mini" id="SubletPricePerBedroom" type="text">
					</div>
					<label>
						<strong>Rate:</strong>
						<select id = 'SubletRate' class="input-small">
							<option>Monthly</option>
						</select>
					</label>
				</div>
				<br>
				<div>
					<label><strong>Unique Description:</strong></label>
					<textarea id = 'SubletDescription' rows="3" cols="1000"></textarea>
				</div>
			</form>
			<form class="form-horizontal" id="bottom-form">
				<h4>Additional Details (Optional)</h4>
				<div class = 'row-fluid'>
					<div class = 'span6'>
						
						
						<div class="control-group">
    						<label class="control-label" for="SubletFurnished"><strong>Furnished:</strong></label>
    						<div class="controls">
      					
      							<select class="input-mini" id='SubletFurnished'>
									<option value="">No</option>
									<option value="">Paritally</option>
									<option value="">Fully</option>
								</select>

    						</div>
  						</div>

						<div class="control-group">
    						<label class="control-label" for="SubletBathroom"><strong>Bathroom:</strong></label>
    						<div class="controls">
      					
      							<select class="input-small" id='SubletBathroom'>
									<option>Included</option>
									<option>Monthly Fee</option>
								</select>

    						</div>
  						</div>
  						<div class="control-group">
    						<label class="control-label" for="SubletParking"><strong>Parking:</strong></label>
    						<div class="controls">
      					
      							<select class="input-small" id='SubletParking'>
									<option>Available</option>
									<option>Monthly Fee</option>
								</select>

    						</div>
						</div>
						
						<div class="control-group">
    						<label class="control-label" for="SubletAC"><strong>A/C:</strong></label>
    						<div class="controls">
      							<select class="input-small" id='SubletAC'>
									<option>Available</option>
									<option>Monthly Fee</option>
								</select>
    						</div>
						</div>

					</div>

					<div class = 'span6'>
						
						<div class="control-group">
    						<label class="control-label" for="SubletDeposit"><strong>Deposit:</strong></label>
    						<div class="controls">
      							<div class="input-prepend">
									<span class="add-on">$</span>
									<input class="input-mini" id="SubletDeposit" type="text">
								</div>
    						</div>
						</div>


						<div class="control-group">
    						<label class="control-label" for="SubletUtilities"><strong>Utilities:</strong></label>
    						<div class="controls">
      							<div class="input-prepend">
									<span class="add-on">$</span>
									<input class="input-mini" id="SubletUtilities" type="text">
								</div>
    						</div>
						</div>


						<div class="control-group">
    						<label class="control-label" for="SubletOtherFees"><strong>Other Fees:</strong></label>
    						<div class="controls">
      							<select id = 'SubletOtherFees' class="input-small">
									<option>Yes</option>
									<option>No</option>
								</select>
    						</div>
						</div>
						

						<div class="control-group">
    						<label class="control-label" for="SubletOtherKeyCosts"><strong>Extra Key Costs:</strong></label>
    						<div class="controls">
      							<div class="input-prepend">
									<span class="add-on">$</span>
									<input class="input-mini" id="SubletOtherKeyCosts" type="text">
								</div>
    						</div>
						</div>	
					
					</div>
					
					
				</div>

				
				
				
				
				
	
			</form>
			<div class="modal-footer">
				<button class="btn btn-inverse pull-left back-btn">Back</button>
				<button class="btn btn-inverse pull-right next-btn">Next</button>
			</div>
		</div>
		<div class="modal-body step" id="addinfo-step">
			Additional Info Step
			<div class="modal-footer">
				<button class="btn btn-inverse pull-left back-btn">Back</button>
				<button class="btn btn-inverse pull-right next-btn">Next</button>
			</div>			
		</div>
		<div class="modal-body step" id="photo-step">
			<?php echo $this->Element('photo_manager');?>
			<div class="modal-footer">
				<button class="btn btn-inverse pull-left back-btn">Back</button>
				<button class="btn btn-warning pull-right post-btn">Post</button>
			</div>			
		</div>
		<div class="modal-body step" id="share-step">
			Share Step
		</div>
	</div>
</div>


<?php 
	$this->Js->buffer('
		$("#photo-step").siblings().hide();
		$(".next-btn").click(function(){
			$(this).closest(".step").hide().next(".step").show();
		});
		$(".back-btn").click(function(){
			$(this).closest(".step").hide().prev(".step").show();
		});

	');
?>
	