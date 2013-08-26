<?php echo $this->Html->css('/less/Dashboard/navigation-bar.less?','stylesheet/less', array('inline' => false)); ?>

<ul id="navigation-bar" class="nav nav-list">
	<?php
	if (intval($AuthUser['user_type']) == 1)
	{
	?>
	<li id="create-listing">
		<a href="#">
			<i class="icon-plus-sign icon-large"></i>
			CREATE A NEW LISTING
		</a>
	</li>
	<li class="spacer"></li>
	<? } ?>
	<li>
		<a href="/map">
			<i class="icon-map-marker icon-large"></i>
			Return to Map
		</a>
	</li>
	<li>
		<a id = 'account-content-header' href="#" class = 'content-header' classname = 'account'>
			<i class="icon-user icon-large"></i>
			Account
		</a>
	</li>
	<li>
		<a id='messages-content-header' href="#" class = 'content-header list-dropdown-header' classname = 'messages'>
			<i class="icon-envelope icon-large"></i>
			Messages
			<div id="message_count" class="notification_count pull-right">0</div>
		</a>
	</li>
	<?php
	if (intval($AuthUser['user_type']) == 1)
	{
	?>
	<li>
		<a id='rental-content-header' href="#" class = 'content-header list-dropdown-header' classname = 'rentals'>
			<i class="icon-home icon-large"></i>
			Rentals
			<div id="rentals_count" class="notification_count pull-right">0</div>
		</a>
	</li>
	<?php }
	$user_type = intval($AuthUser['user_type']);
	if ($user_type == 1 || $user_type == 3)
	{
	?>
	<li>
		<a id='featured-listing-content-header' href="#" class = 'content-header list-header' classname = 'featuredlisting'>
			<i class="icon-star icon-large"></i>
			Feature Listing
		</a>
	</li>
	<?php } ?>
<!--	<li>
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
	</li> -->
</ul>
<div class="row-fluid dropdowns_container">
	<ul class="nav nav-list hide list-dropdown span12" id = "messages_list">
		<li class="row-fluid">
			<div class="span12 nav-label">
				MESSAGES
			</div>
		</li>
		<li class="row-fluid">
			<div class="span12">
				<input class="dropdown-search span9" type="text" data-filter-list="#messages_list_content"><i class="icon-search icon-large"></i>
			</div>
		</li>
		<ul id = "messages_list_content" class="list_content">
		</ul>
	</ul>
	<ul class="nav nav-list hide list-dropdown span12" id = "rentals_list">
		<li class="row-fluid">
			<div class="span12 nav-label">
				RENTALS
			</div>
		</li>
		<li class="row-fluid">
			<div class="span12">
				<input class="dropdown-search span9" type="text" data-filter-list="#rentals_list_content"><i class="icon-search icon-large"></i>
			</div>
		</li>
		<ul id = "rentals_list_content" class="list_content">
		</ul>
	</ul>
</div>
	<!--
	<div class="row-fluid">
	<ul class="nav nav-list hide list-dropdown" id = "sublet_list">
		<li class="nav-header">SUBLETS</li>
	</ul>
	</div>
	<div class="row-fluid">
	<ul class="nav nav-list hide list-dropdown" id = "parking_list">
		<li class="nav-header">PARKING</li>
	</ul>
	</div>
	-->