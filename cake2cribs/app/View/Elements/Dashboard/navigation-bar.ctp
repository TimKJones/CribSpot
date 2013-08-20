<?php echo $this->Html->css('/less/Dashboard/navigation-bar.less?','stylesheet/less', array('inline' => false)); ?>

<ul id="navigation-bar" class="nav nav-list">
	<li id="create-listing">
		<a href="#">
			<i class="icon-plus-sign icon-large"></i>
			CREATE A NEW LISTING
		</a>
	</li>
	<li class="spacer"></li>
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
	<ul class="nav nav-list hide list-dropdown" id = "Rental_list">
		<li class="nav-header">MESSAGES</li>
	</ul>
	<li>
		<a id='rental-content-header' href="#" class = 'content-header list-dropdown-header' classname = 'rentals'>
			<i class="icon-home icon-large"></i>
			Rentals
			<div id="rentals_count" class="notification_count pull-right">0</div>
		</a>
	</li>
	
	<ul class="nav nav-list hide list-dropdown" id = "rentals_list">
		<li class="nav-header">RENTALS</li>
	</ul>

	<li>
		<a href="#" class = 'featured-listing-content-header content-header' classname = 'featuredlisting'>
			<i class = "icon-star icon-large"></i>
			Featured Listings
		</a>
	</li>
</ul>

<div class="row-fluid">
	<ul class="nav nav-list hide list-dropdown span12" id = "messages_list">
		<li class="nav-header">MESSAGES</li>
	</ul>
</div>
<div class="row-fluid">
	<ul class="nav nav-list hide list-dropdown span12" id = "rentals_list">
		<li class="nav-header">RENTALS</li>
	</ul>
</div>

