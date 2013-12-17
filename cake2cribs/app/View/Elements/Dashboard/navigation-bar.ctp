<?php echo $this->Html->css('/less/Dashboard/navigation-bar.less?v=2','stylesheet/less', array('inline' => false)); ?>

<ul id="navigation-bar" class="nav nav-list">
	<?php
	if (intval($AuthUser['user_type']) == 1)
	{
	?>
	<li class="create-listing">
		<a href="#" data-listing-type="rental">
			<i class="icon-plus-sign icon-large"></i>
			CREATE A NEW RENTAL
		</a>
	</li>
	<li class="spacer"></li>
	<?php }
	if (intval($AuthUser['user_type']) == 0)
	{
	?>
	<li class="create-listing">
		<a href="#" data-listing-type="sublet">
			<i class="icon-plus-sign icon-large"></i>
			CREATE A NEW SUBLET
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
		<a id = 'overview-content-header' href="#" class = 'content-header active' classname = 'overview'>
			<i class="icon-bar-chart icon-large"></i>
			Overview
		</a>
</li>
	<?php
	if (intval($AuthUser['user_type']) < 2)
	{
	?>
	<li>
		<a id = 'account-content-header' href="#" class = 'content-header' classname = 'account'>
			<i class="icon-user icon-large"></i>
			Account
		</a>
	</li>
	<?php } ?>

<?php $user_type = intval($AuthUser['user_type']);
	if ($user_type < 2)
	{
	?>
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
<?php } ?>
	<?php 
	if ($user_type === 1 || $user_type === 3)
	{
	?>
	<li>
		<a id='rental-content-header' href="#" class = 'content-header list-dropdown-header' classname = 'rental'>
			<i class="icon-home icon-large"></i>
			Rentals
			<div id="rental_count" class="notification_count pull-right">0</div>
		</a>
	</li>

	<?php } ?>
	<?php 
	if (intval($AuthUser['user_type']) === 0)
	{
	?>
	<li>
		<a id='sublet-content-header' href="#" class = 'content-header list-dropdown-header' classname = 'sublet'>
			<i class="icon-home icon-large"></i>
			Sublets
			<div id="sublet_count" class="notification_count pull-right">0</div>
		</a>
	</li>

	<?php } ?>
	<?php $user_type = intval($AuthUser['user_type']);
	if ($user_type === 1 || $user_type === 2)
	{
	?>
	<li>
		<a id='featured-listing-content-header' href="#" class = 'content-header list-header' classname = 'featuredlisting'>
			<i class="icon-star icon-large"></i>
			Feature Listing
		</a>
	</li>
	<?php } ?>

<div class="row-fluid dropdowns_container">
	<?php $user_type = intval($AuthUser['user_type']);
	if ($user_type < 2)
	{
	?>
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
	<?php } ?>
	<ul class="nav nav-list hide list-dropdown span12" id = "rental_list">
		<li class="row-fluid">
			<div class="span12 nav-label">
				RENTALS
			</div>
		</li>
		<li class="row-fluid">
			<div class="span12">
				<input class="dropdown-search span9" type="text" data-filter-list="#rental_list_content"><i class="icon-search icon-large"></i>
			</div>
		</li>
		<ul id = "rental_list_content" class="list_content" data-listing-type="rental">
		</ul>
	</ul>
		<ul class="nav nav-list hide list-dropdown span12" id = "sublet_list">
		<li class="row-fluid">
			<div class="span12 nav-label">
				SUBLETS
			</div>
		</li>
		<li class="row-fluid">
			<div class="span12">
				<input class="dropdown-search span9" type="text" data-filter-list="#sublet_list_content"><i class="icon-search icon-large"></i>
			</div>
		</li>
		<ul id = "sublet_list_content" class="list_content" data-listing-type="sublet">
		</ul>
	</ul>
</div>
