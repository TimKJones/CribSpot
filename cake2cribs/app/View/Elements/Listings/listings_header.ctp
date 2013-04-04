
<?php echo $this->Html->css("listingsDropDown"); ?>
<div id = 'listings-content-header' class = 'content-header minimized' classname = 'listings'>
	<span>My Listings</span>
	<i id = 'toggle-listings' class = 'toggle-drop-down icon-caret-right'></i>
	<div class = 'header-count' id = 'listings_count'><span><?php echo count($sublets);?></span></div>
</div>
<div class = 'drop-down'>
	<div class = 'drop-down-list listings_list'>
	
		<?php
		foreach($sublets as $sublet)
		{?>
			<?php 
				$id = $sublet['Sublet']['id'];
			?>
				<div id = '<?php echo $id; ?>' class="listing-list-item">
						<?php echo $sublet['Marker']['street_address']; ?>
				</div>

			<!-- echo $this->element('Listings/listings_header', $sublet); -->
		<?php }
		?>
	
	</div>
</div>
<script>
$(document).ready(function(){
	$('.listing-list-item').click(function(e){
		A2Cribs.SubletEdit.EditSublet(e.target.id);
	});	
});
</script>