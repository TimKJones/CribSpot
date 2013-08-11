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
		<a href="#">
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
		<a href="#">
			<i class="icon-envelope icon-large"></i>
			Messages
		</a>
	</li>
	<li>
		<a id='rental-content-header' href="#" class = 'content-header list-dropdown-header' classname = 'rentals'>
			<i class="icon-home icon-large"></i>
			Rentals
		</a>
	</li>
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
<ul class="nav nav-list hide list-dropdown" id = "Rental_list">
	<li class="nav-header">MESSAGES</li>
</ul>
<ul class="nav nav-list hide list-dropdown" id = "rentals_list">
	<li class="nav-header">RENTALS</li>
</ul>
<!--<ul class="nav nav-list hide list-dropdown" id = "sublet_list">
	<li class="nav-header">SUBLETS</li>
</ul>
<ul class="nav nav-list hide list-dropdown" id = "parking_list">
	<li class="nav-header">PARKING</li>
</ul>-->