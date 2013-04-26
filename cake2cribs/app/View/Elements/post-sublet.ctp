<?= $this->Html->css('/less/post-sublet.less?','stylesheet/less', array('inline' => false)); ?>
<?= $this->Html->css('/less/checkbox.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('listing-popup-verifications'); ?>

<div class="listing-popup modal container-fluid">
	<div id="sublet-id" class="hide"></div>
	<div class="modal-header">
		<i class="sublet-name title">Post Your Sublet</i>
		<div class = 'progress-wrapper'>
			<?php echo $this->element('post-sublet-progress');?>
		</div>
		<div id="modal-close-button" class="close" data-dismiss="modal"></div>
	</div>
	<div>
		<div class="modal-body step" id="address-step">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span7">
						<div class="row-fluid">
							<label class="span3"><strong>College:</strong></label>
							<input type="text" class="span9">
						</div>
						<div class="row-fluid">
							<label class="span5"><strong>Building Name:</strong></label>
							<input type="text" class="span7">
						</div>
						<div class="row-fluid">
							<label class="span5"><strong>Street Address:</strong></label>
							<input type="text" class="span7">
						</div>
						<div class="row-fluid">
							<label class="span2"><strong>Type:</strong></label>
							<select class="span5">
								<option>Apartment</option>
								<option>House</option>
								<option>Duplex</option>
							</select>
							<button class="btn btn-info btn-small span5 pull-right"><i class="icon-map-marker icon-large"></i> Place on Map</button>
						</div>
						<div class="row-fluid">
							<div class="span12" id="map-message">Please verify that the marker to the right is on the correct location. If not, please click and drag the marker to the correct spot on the map.</div>
							<img id="map-message-arrow" src="/img/messages/arrow-right.png">
						</div>
						<div class="row-fluid">
							<label class="span4"><strong>Unit Number:</strong></label>
							<input type="text" class="span4">
						</div>
					</div>
					<div class="span5">
						<div class="row-fluid">
							<div id="map-background" class="span12">
								<div id="correctLocationMap"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
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
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span12">
						<strong>Housemate Information</strong>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span6">
						<div class="row-fluid">
							<label class="span8"><strong>Estimated Housemates:</strong></label>
							<select class="span3">
								<option>0</option>
								<option>1</option>
								<option>2</option>
								<option>3</option>
								<option>4</option>
								<option>5+</option>
							</select>
						</div>
					</div>
					<div class="span6">
						<div class="row-fluid">
							<label class="span7"><strong>Are They Students?:</strong></label>
							<select class="span4">
								<option>Yes</option>
								<option>No</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span4">
						<div class="row-fluid">
							<label class="span4"><strong>Type:</strong></label>
							<select class="span8">
								<option>Undergrad</option>
								<option>Graduate</option>
							</select>
						</div>
					</div>
					
					<div class="span4">
						<div class="row-fluid">
							<label class="span4"><strong>Year:</strong></label>
							<select class="span8">
								<option>Freshman</option>
								<option>Sophomore</option>
								<option>Junior</option>
								<option>Senior</option>
							</select>
						</div>
					</div>
					<div class="span4">
						<div class="row-fluid">
							<label class="span5"><strong>Gender:</strong></label>
							<select class="span6">
								<option>Male</option>
								<option>Female</option>
								<option>Mixed</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<label class="span2"><strong>Majors:</strong></label>
					<input type="text" class="span10">
				</div>
				<div class="row-fluid">
					Longer Sublet Description (Optional)
				</div>
				<div class="row-fluid" id='cock-munch'>
					<textarea class="span12" rows="5"></textarea>
				</div>
			</div>

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
		$("#address-step").siblings().hide();

		$(".next-btn").click(function(){
			$(this).closest(".step").hide().next(".step").show();
		});
		$(".back-btn").click(function(){
			$(this).closest(".step").hide().prev(".step").show();
		});

	');
?>
	
