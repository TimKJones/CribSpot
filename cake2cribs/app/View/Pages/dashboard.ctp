<?php
	echo $this->Html->css('/js/slickgrid/slick.grid.css', null, array('inline' => false));
	echo $this->Html->css('/js/slickgrid/css/smoothness/jquery-ui-1.8.16.custom.css', null, array('inline' => false));
	echo $this->Html->css('/js/slickgrid/examples/examples.css', null, array('inline' => false));
	echo $this->Html->css('/js/slickgrid/controls/slick.columnpicker.css', null, array('inline' => false));

	echo $this->Html->script('src/Rental.js', array('inline' => false));
	echo $this->Html->script('src/RentalSave.js', array('inline' => false));


	echo $this->Html->script('slickgrid/lib/jquery-1.7.min.js', array('inline' => false));
	echo $this->Html->script('slickgrid/lib/jquery-ui-1.8.16.custom.min.js', array('inline' => false));
	echo $this->Html->script('slickgrid/lib/jquery.event.drag-2.2.js', array('inline' => false));

	echo $this->Html->script('slickgrid/slick.core.js', array('inline' => false));
	echo $this->Html->script('slickgrid/plugins/slick.checkboxselectcolumn.js', array('inline' => false));
	echo $this->Html->script('slickgrid/plugins/slick.autotooltips.js', array('inline' => false));
	echo $this->Html->script('slickgrid/plugins/slick.cellrangedecorator.js', array('inline' => false));
	echo $this->Html->script('slickgrid/plugins/slick.cellrangeselector.js', array('inline' => false));
	echo $this->Html->script('slickgrid/plugins/slick.cellcopymanager.js', array('inline' => false));
	echo $this->Html->script('slickgrid/plugins/slick.cellselectionmodel.js', array('inline' => false));
	echo $this->Html->script('slickgrid/plugins/slick.rowselectionmodel.js', array('inline' => false));
	echo $this->Html->script('slickgrid/controls/slick.columnpicker.js', array('inline' => false));
	echo $this->Html->script('slickgrid/slick.formatters.js', array('inline' => false));
	echo $this->Html->script('slickgrid/slick.editors.js', array('inline' => false));
	echo $this->Html->script('slickgrid/slick.grid.js', array('inline' => false));
?>



<!-- Header -->
<!--<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner"></div>
</div>-->

<div class="container-fluid">
	<div class="row-fluid">

		<!-- Sidebar -->
		<div class="span2">
			<ul class="nav nav-list">
				<li>
					<a href="#">
						<i class="icon-plus-sign icon-large"></i>
						CREATE A NEW LISTING
					</a>
				</li>
				<li>
					<a href="#">
						<i class="icon-map-marker icon-large"></i>
						Return to Map
					</a>
				</li>
				<li>
					<a href="#">
						<i class="icon-user icon-large"></i>
						Account
					</a>
				</li>
				<li>
					<a href="#">
						<i class="icon-envelope icon-large"></i>
						Messages
					</a>
				</li>
				<li>
					<a href="#">
						<i class="icon-home icon-large"></i>
						Rentals
					</a>
				</li>
				<li>
					<a href="#">
						<i></i>
						Sublets
					</a>
				</li>
				<li>
					<a href="#">
						<i></i>
						Parking
					</a>
				</li>
			</ul>
			<ul class="nav nav-list">
				<li class="nav-header">RENTALS</li>
				<li></li>
			</ul>
		</div>

		<!-- Rest of layout -->
		<div class="span10">

			<!-- Rental Preview -->
			<div class="row-fluid">
			</div>

			<!-- Rental Tabs and Buttons -->
			<div class="row-fluid">

				<!-- Rental Tabs -->
				<div class="span12 tabbable"> <!-- Only required for left/right tabs -->
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#overview_grid" data-toggle="tab">
								OVERVIEW
							</a>
						</li>
						<li>
							<a href="#features_grid" data-toggle="tab">
								FEATURES
							</a>
						</li>
						<li>
							<a href="#amenities_grid" data-toggle="tab">
								AMENITIES
							</a>
						</li>
						<li>
							<a href="#utilites_grid" data-toggle="tab">
								UTILITIES
							</a>
						</li>
						<li>
							<a href="#fees_grid" data-toggle="tab">
								FEES
							</a>
						</li>
						<li>
							<a href="#description_grid" data-toggle="tab">
								DESCRIPTION
							</a>
						</li>
						<li>
							<a href="#pictures_grid" data-toggle="tab">
								PICTURES
							</a>
						</li>
						<li>
							<a href="#contact_grid" data-toggle="tab">
								CONTACT
							</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="overview_grid" style="height:300px;">
							<p>I'm in Section 1.</p>
						</div>
						<div class="tab-pane" id="features_grid" style="height:300px;">
							<p>Howdy, I'm in Section 2.</p>
						</div>
						<div class="tab-pane" id="amenities_grid" style="height:300px;">
							<p>Howdy, I'm in Section 3.</p>
						</div>
						<div class="tab-pane" id="utilites_grid" style="height:300px;">
							<p>Howdy, I'm in Section 4.</p>
						</div>
						<div class="tab-pane" id="fees_grid" style="height:300px;">
							<p>Howdy, I'm in Section 5.</p>
						</div>
						<div class="tab-pane" id="description_grid" style="height:300px;">
							<p>Howdy, I'm in Section 6.</p>
						</div>
					</div>
				</div>

				<!-- Rental Buttons -->
				<!--<div class="span4">
					<div class="btn-group">
						<button class="btn">Edit</button>
						<button class="btn">Copy</button>
						<button class="btn">Delete</button>
					</div>
				</div>-->
			</div>

			<div class="row-fluid">
				<a href="#" onclick="A2Cribs.RentalSave.CreateSubRental()"><i class="icon-plus-sign icon-large"></i> Add another unit or floorplan style for this address</a>
			</div>
		</div>

	</div>
</div>

<?php 
	$this->Js->buffer('
		A2Cribs.RentalSave = new A2Cribs.RentalSave();
	');
?>