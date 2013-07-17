<?php
	echo $this->Html->css('/js/slickgrid/slick.grid.css', null, array('inline' => false));
	echo $this->Html->css('/js/slickgrid/css/smoothness/jquery-ui-1.8.16.custom.css', null, array('inline' => false));
	//echo $this->Html->css('/js/slickgrid/examples/examples.css', null, array('inline' => false));
	echo $this->Html->css('/js/slickgrid/controls/slick.columnpicker.css', null, array('inline' => false));
	echo $this->Html->css('/less/Dashboard/rentals_window.less?','stylesheet/less', array('inline' => false));

	echo $this->Html->script('src/UILayer/UILayer.js', array('inline' => false));
	echo $this->Html->script('src/UILayer/Rentals.js', array('inline' => false));
	echo $this->Html->script('src/UILayer/Fees.js', array('inline' => false));
	
	echo $this->Html->script('src/Rental.js', array('inline' => false));
	echo $this->Html->script('src/RentalSave.js', array('inline' => false));

	//echo $this->Html->script('slickgrid/lib/jquery-ui-1.8.16.custom.min.js', array('inline' => false));
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
	echo $this->Html->script('slickgrid/custom.formatters.js', array('inline' => false));
	echo $this->Html->script('slickgrid/slick.editors.js', array('inline' => false));
	echo $this->Html->script('slickgrid/custom.editors.js', array('inline' => false));
	echo $this->Html->script('slickgrid/slick.grid.js', array('inline' => false));
?>

<!-- Rental Preview -->
<div id="rentals_preview" class="row-fluid">
	<img src="" alt="Nice" class="img-polaroid pull-left" style="width:80px; height:70px;">
	<address id="rentals_address">
		<strong>Zaragon</strong><br>
		795 Folsom Ave, Suite 600<br>
	</address>
	<div class="btn-group pull-right">
		<button class="btn btn-small" id="rentals_edit">Edit</button>
		<!--<button class="btn">Copy</button>-->
		<button class="btn btn-small" id="rentals_delete">Delete</button>
	</div>
</div>

<!-- Rental Tabs and Buttons -->
<div class="row-fluid">

	<!-- Rental Tabs -->
	<div class="span12 tabbable"> <!-- Only required for left/right tabs -->
		<ul class="nav nav-tabs grid-tabs">
			<li class="active">
				<a href="#overview_grid" class="rentals_tab" data-toggle="tab">
					OVERVIEW
				</a>
			</li>
			<li>
				<a href="#features_grid" class="rentals_tab" data-toggle="tab">
					FEATURES
				</a>
			</li>
			<li>
				<a href="#amenities_grid" class="rentals_tab" data-toggle="tab">
					AMENITIES
				</a>
			</li>
			<li>
				<a href="#buildingamenities_grid" class="rentals_tab" data-toggle="tab">
					BUILDING AMENITIES
				</a>
			</li>
			<li>
				<a href="#utilities_grid" class="rentals_tab" data-toggle="tab">
					UTILITIES
				</a>
			</li>
			<li>
				<a href="#fees_grid" class="rentals_tab" data-toggle="tab">
					FEES
				</a>
			</li>
			<li>
				<a href="#description_grid" class="rentals_tab" data-toggle="tab">
					DESCRIPTION
				</a>
			</li>
			<li>
				<a href="#picture_grid" class="rentals_tab" data-toggle="tab">
					PICTURES
				</a>
			</li>
			<li>
				<a href="#contact_grid" class="rentals_tab" data-toggle="tab">
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
			<div class="tab-pane" id="utilities_grid" style="height:300px;">
				<p>Howdy, I'm in Section 4.</p>
			</div>
			<div class="tab-pane" id="buildingamenities_grid" style="height:300px;">
				<p>Howdy, I'm in Section 4.</p>
			</div>
			<div class="tab-pane" id="fees_grid" style="height:300px;">
				<p>Howdy, I'm in Section 5.</p>
			</div>
			<div class="tab-pane" id="description_grid" style="height:300px;">
				<p>Howdy, I'm in Section 6.</p>
			</div>
			<div class="tab-pane" id="picture_grid" style="height:300px;">
				<p>Howdy, I'm in Section 6.</p>
			</div>
			<div class="tab-pane" id="contact_grid" style="height:300px;">
				<p>Howdy, I'm in Section 6.</p>
			</div>
		</div>
	</div>
</div>

<div class="row-fluid">
	<a href="#" onclick="A2Cribs.RentalSave.AddNewUnit()"><i class="icon-plus-sign icon-large"></i> Add another unit or floorplan style for this address</a>
</div>

<?php 
	$this->Js->buffer('
		A2Cribs.RentalSave = new A2Cribs.RentalSave();
	');
?>