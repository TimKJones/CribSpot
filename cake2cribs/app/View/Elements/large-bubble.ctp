<?= $this->Html->css('/less/large-bubble.less?v=3','stylesheet/less', array('inline' => false)); ?>

<div class="large-bubble hide">
	<div class="click_header"><div class="building_name clear_field">10202  West Chester Rd.</div><div class="close_button">&times;</div></div>
	<div class="property_image"></div>
	<div class="share_section">
		<div class="unit_style_description clear_field">Style - Bi-level 3 beds</div>
		<div class="share_button">
			<a class="link_share share_btn" href="#"><i class="icon-link"></i></a>
			<a class="facebook_share share_btn" href="#"><i class="icon-facebook"></i></a>
			<a class="twitter_share share_btn" href="#"><i class="icon-twitter"></i></a>
			<a class="favorite_listing share_btn" href="#"><i class="icon-heart"></i></a>
		</div>
	</div>
	<div class="general_info">
		<div class="bubble_label price_label">$</div><div class="rent clear_field">700</div><div class="per_month">/m</div>
		<div class="beds clear_field">2</div><div class="bed_desc">Beds</div>
		<div class="available">Available</div>
	</div>
	<div class="additional_info">
		<div class="start_date date_range clear_field">Aug 23rd, 2013</div><span class="end_date_box hide">&nbsp;-&nbsp;<div class="end_date lease_length clear_field">Aug 23rd, 2013</div></span><span class="lease_box">&nbsp;&bull;&nbsp;<div class="lease_length clear_field"></div><div class="bubble_label">&nbsp;month(s)</div></span><br>
		<div class="unit_type clear_field">Apartment</div>&nbsp;&bull;&nbsp;<div class="baths clear_field">2</div><div class="bubble_label">&nbsp;bath(s)</div>
		<div class="property_manager clear_field">Investor's Property Management</div>
		<div class="verified hide">VERIFIED</div>
	</div>
	<!-- <a href="#" class="schedule_tour"><div>Request a tour now</div></a> -->
	<?php
	if (intval($active_listing_type) === 0)
	{ ?>
	<div class="action_buttons rental">
		<a class="full_page_link" href="#">More Info <i class="icon-plus"></i></a>
		<a class="full_page_contact" href="#">Contact <i class="icon-comment"></i></a>
		<a href="#" class="website_link">Website <i class="icon-share-alt"></i></a>
	</div>
	<?php
	}
	elseif (intval($active_listing_type) === 1)
	{
	?>
	<div class="action_buttons sublet">
		<a class="full_page_link" href="#">More Info <i class="icon-plus"></i></a>
		<a class="full_page_contact" href="#">Contact <i class="icon-comment"></i></a>
	</div>
	<?php } ?>
</div>

