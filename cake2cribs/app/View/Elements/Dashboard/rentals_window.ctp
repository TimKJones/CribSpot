<?php
	echo $this->Html->css('/js/slickgrid/slick.grid.css', null, array('inline' => false));
	echo $this->Html->css('/js/slickgrid/css/smoothness/jquery-ui-1.8.16.custom.css', null, array('inline' => false));
	//echo $this->Html->css('/js/slickgrid/examples/examples.css', null, array('inline' => false));
	echo $this->Html->css('/js/slickgrid/controls/slick.columnpicker.css', null, array('inline' => false));
	echo $this->Html->css('/less/Dashboard/rentals_window.less?v=2','stylesheet/less', array('inline' => false));

if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	echo $this->Html->script('src/UILayer/UILayer.js', array('inline' => false));
	echo $this->Html->script('src/UILayer/Rentals.js', array('inline' => false));
	echo $this->Html->script('src/UILayer/Fees.js', array('inline' => false));
	echo $this->Html->script('src/Rental.js', array('inline' => false));
	echo $this->Html->script('src/RentalSave.js', array('inline' => false));
	echo $this->Html->script('src/MiniMap', array('inline' => false));
}

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
	<div id="correctLocationMap" class="pull-left">
	</div>
	<address class="pull-left">
		<div id="rentals_address"></div>
		<button class="btn btn-small edit_marker">EDIT ADDRESS &amp; TYPE</button>
		<a href="#" id="add_new_unit" onclick="A2Cribs.RentalSave.AddNewUnit()"><i class="icon-plus-sign icon-large"></i> Add another unit or floorplan style for this address</a>
	</address>
</div>

<button class="btn btn-small" id="feature-btn">Feature This Listing</button>

<div class="row-fluid">
	<div class="btn-group pull-left edit_buttons">
		<button class="btn btn-small" id="rentals_edit">Edit</button>
		<!--<button class="btn">Copy</button>-->
		<button class="btn btn-small" id="rentals_delete">Delete</button>
	</div>
	<div class="pull-right info_text">Don't have time to post? Send them in any format to info@cribspot.com and we'll post for you...for FREE!</div>
</div>

<!-- Rental Tabs and Buttons -->
<div class="row-fluid">

	<!-- Rental Tabs -->
	<div class="span12 tabbable"> <!-- Only required for left/right tabs -->
		<ul class="nav nav-tabs grid-tabs">
			<li class="active">
				<a href="#" data-target="#overview_grid" class="rentals_tab">
					OVERVIEW
				</a>
			</li>
			<li>
				<a href="#" data-target="#features_grid" class="rentals_tab">
					FEATURES
				</a>
			</li>
			<li>
				<a href="#" data-target="#amenities_grid" class="rentals_tab">
					UNIT AMENITIES
				</a>
			</li>
			<li>
				<a href="#" data-target="#buildingamenities_grid" class="rentals_tab">
					BUILDING AMENITIES
				</a>
			</li>
			<li>
				<a href="#" data-target="#utilities_grid" class="rentals_tab">
					UTILITIES
				</a>
			</li>
			<li>
				<a href="#" data-target="#fees_grid" class="rentals_tab">
					FEES
				</a>
			</li>
			<li>
				<a href="#" data-target="#description_grid" class="rentals_tab">
					DESCRIPTION
				</a>
			</li>
			<li>
				<a href="#" data-target="#picture_grid" class="rentals_tab">
					PICTURES
				</a>
			</li>
			<li>
				<a href="#" data-target="#contact_grid" class="rentals_tab">
					CONTACT
				</a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane grid-pane active" id="overview_grid">
				<p>I'm in Section 1.</p>
			</div>
			<div class="tab-pane grid-pane" id="features_grid">
				<p>Howdy, I'm in Section 2.</p>
			</div>
			<div class="tab-pane grid-pane" id="amenities_grid">
				<p>Howdy, I'm in Section 3.</p>
			</div>
			<div class="tab-pane grid-pane" id="utilities_grid">
				<p>Howdy, I'm in Section 4.</p>
			</div>
			<div class="tab-pane grid-pane" id="buildingamenities_grid">
				<p>Howdy, I'm in Section 4.</p>
			</div>
			<div class="tab-pane grid-pane" id="fees_grid">
				<p>Howdy, I'm in Section 5.</p>
			</div>
			<div class="tab-pane grid-pane" id="description_grid">
				<p>Howdy, I'm in Section 6.</p>
			</div>
			<div class="tab-pane grid-pane" id="picture_grid">
				<p>Howdy, I'm in Section 6.</p>
			</div>
			<div class="tab-pane grid-pane" id="contact_grid">
				<p>Howdy, I'm in Section 6.</p>
			</div>
		</div>
	</div>
</div>

<?php 
	$this->Js->buffer('
		A2Cribs.RentalSave = new A2Cribs.RentalSave(' . json_encode($dropdowns) . ', "' . $user["User"]["email"] . '", "' . $user["User"]["phone"] . '");
	');
?>
