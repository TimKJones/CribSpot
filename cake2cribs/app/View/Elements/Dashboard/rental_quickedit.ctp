<?= $this->Html->css('/less/Dashboard/rental_quickedit.less?v=4','stylesheet/less', array('inline' => false)) ?>

<div id="rental_quickedit" class="container_fluid">
	<div class="row-fluid">
		<div class="searchbox pull-left"><input type="text" class="search_rentals"><i class="icon-search"></i></div>
		<select id="sort_availablity" class="pull-left" name="Sort Listings">
			<option>Show All</option>
			<option value="1">Available Only</option>
			<option value="0">Leased Only</option>
		</select>
		<a class="toggle_all_listings" href="#">Open all listings</a>
	</div>
	<div id="rental_preview_list">
		<!-- Filled with rental_previews -->
	</div>
</div>
