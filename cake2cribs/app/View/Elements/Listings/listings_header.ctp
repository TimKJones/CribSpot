<?php echo $this->Html->css("listingsDropDown"); ?>
<div id = 'listings-content-header' class = 'content-header minimized' classname = 'listings'>
	<span>My Listings</span>
	<i id = 'toggle-listings' class = 'toggle-drop-down icon-caret-right'></i>
	<div class = 'header-count' id = 'listings_count'><span><?php echo count($sublets);?></span></div>
</div>
<div class = 'drop-down'>
	<div class = 'drop-down-list listings_list'>
<?php
	for ($i = 0; $i < count($sublets); $i++)
	{
		echo '<div id="' . $sublets[$i]['Sublet']['id'] . '" class="listing_list_item" >' . $sublets[$i]['Marker']['street_address'] . '</div>';
	}
?>
	
</div>
</div>
<script>
$(document).ready(function(){
	$('.listing_list_item').click(function(e){
		A2Cribs.SubletEdit.EditSublet(e.target.id);
	});	
});
</script>