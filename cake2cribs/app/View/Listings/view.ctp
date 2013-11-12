<?php echo $this->Html->css('/less/Listing/full_page.less?v=4','stylesheet/less', array('inline' => false)); ?>
<?php 
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	echo $this->Html->script('src/FullListing.js', array('inline' => false));
}
	$name = "";
	if (strlen($listing["Marker"]["alternate_name"]) != 0)
		$name = $listing["Marker"]["alternate_name"] . " - ";

	$this->set('title_for_layout', $name . $listing["Marker"]["street_address"] . ", " . $listing["Marker"]["city"] . ", " .$listing["Marker"]["state"] . " " . $listing["Marker"]["zip"] . " - Cribspot");

	$this->Html->meta('keywords', 
			$listing["Marker"]["alternate_name"] . ", " . $listing["Marker"]["street_address"] . ", off campus housing, student housing, college rental, college sublet, college parking, college sublease", array('inline' => false)
		);

	$this->Html->meta('description', $listing[$listing_type]["description"], array('inline' => false));

	echo $this->element('SEO/places_rich_snippet', array('latitude' => $listing["Marker"]["latitude"], 'longitude' => $listing["Marker"]["longitude"]));

?>
<?php echo $this->element('header', array('show_filter' => false, 'show_user' => true)); ?>
<input id="listing-data" type="hidden" data-listing-id="<?= $listing["Listing"]["listing_id"]; ?>">

<div class="row-fluid full_page">
	<!-- Listing View side bar -->
	<div class="span3 offset1">
		<?= $this->element('Listings/full_page/basic_info'); ?>
		<?= $this->element('Listings/full_page/more_info'); ?>
		<?= $this->element('Listings/full_page/contact_info'); ?>
	</div>
	<?= $this->element('Listings/full_page/main_content', array('listing' => $listing)); ?>
	
</div>

<?php 
	$this->Js->buffer('
		A2Cribs.FullListing.SetupUI(' . $listing["Listing"]["listing_id"] . ');
		A2Cribs.FullListing.Directive(' . $directive . ');
	');
?>