<?php 
	echo $this->Html->css('/less/Listing/full_page.less?v=8','stylesheet/less', array('inline' => false));
	echo $this->Html->css('/less/mobile_listing_hacks.less?v=4','stylesheet/less', array('inline' => false));
?>
<?php 
	if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
		echo $this->Html->script('src/FullListing.js', array('inline' => false));
	}
	$url = 'https://cribspot.com/listings/view/' . $listing["Listing"]["listing_id"] . '/' . $full_address;
	$name = "";
	if (strlen($listing["Marker"]["alternate_name"]) != 0)
		$name = $listing["Marker"]["alternate_name"] . " - ";
	$image_url = 'https://s3-us-west-2.amazonaws.com/cribspot-img/upright_logo.png';
	if (array_key_exists('primary_image', $listing) && array_key_exists('Image', $listing))
		$image_url = 'https://cribspot.com/' . $listing["Image"][$listing["primary_image"]]["image_path"];

	$this->set('title_for_layout', $name . $listing["Marker"]["street_address"] . ", " . $listing["Marker"]["city"] . ", " .$listing["Marker"]["state"] . " " . $listing["Marker"]["zip"] . " - Cribspot");
	$this->set('meta_image', $image_url);
	$this->set('canonical_url', $url);
	$this->set('meta_description', $listing[$listing_type]['description']);

	$this->Html->meta('description', $listing[$listing_type]["description"], array('inline' => false));

	echo $this->Html->meta('canonical', $url, array('rel'=>'canonical', 'type'=>null, 'title'=>null, 'inline' => false));

	echo $this->element('SEO/places_rich_snippet', array('latitude' => $listing["Marker"]["latitude"], 'longitude' => $listing["Marker"]["longitude"]));
	echo $this->element('SEO/facebook_meta_tag', array('title' => 'Check out ' . $name . $listing['Marker']['street_address'] . ' on Cribspot!', 'url' => $url, 'image_path' => $image_url, 'description' => $listing[$listing_type]["description"]));

?>
<?php echo $this->element('header', array('show_filter' => false, 'show_user' => true)); ?>
<input id="listing-data" type="hidden" data-listing-id="<?= $listing["Listing"]["listing_id"]; ?>">

<div class="container">
	<div class="row full_page">
		<!-- Listing View side bar -->
		<div class="span9 middle_content tabbable pull-right">
			<?= $this->element('Listings/full_page/main_content', array('listing' => $listing)); ?>
		</div>
		<div class="span3 pull-left info-bar">
			<?= $this->element('Listings/full_page/basic_info'); ?>
			<?= $this->element('Listings/full_page/more_info'); ?>
			<?= $this->element('Listings/full_page/contact_info'); ?>
			<?= $this->element('Listings/full_page/listing_correction', array('listing_id' => $listing["Listing"]["listing_id"])); ?>
		</div>

	</div>
</div>

<?php 
	$this->Js->buffer('
		A2Cribs.FullListing.SetupUI(' . $listing["Listing"]["listing_id"] . ');
		A2Cribs.FullListing.Directive(' . $directive . ');
	');
?>
