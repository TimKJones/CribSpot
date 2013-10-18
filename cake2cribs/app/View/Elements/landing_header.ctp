<!-- Dropdown for user type 0 -->
<li class="personal_menu personal_menu_0 dropdown <?= ($user_type == 0) ? '' : 'hide' ; ?>">
	<a href="#" id="personal_dropdown_0" role="button" class="dropdown-toggle" data-toggle="dropdown">
		<?php
		echo "<img src='" . $pic_url . "' >";
		echo "<div class='user_name'>" . $name . "</div>";
		?>
		 <b class="caret"></b>
	</a>
	<ul class="dropdown-menu" role="menu" aria-labelledby="personal_dropdown_0">
		<li role="presentation"><?php echo $this->Html->link('My Dashboard', array('controller' => 'dashboard', 'action' => 'index'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>

		<li role="presentation"><?php echo $this->Html->link('My Account', array('controller' => 'users', 'action' => 'accountinfo'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>

		<li role="presentation"><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
	</ul>
</li>


<!-- Dropdown for user type 1 -->
<li class="personal_menu personal_menu_1 dropdown <?= ($user_type == 1) ? '' : 'hide' ; ?>">
	<a href="#" id="personal_dropdown_1" role="button" class="dropdown-toggle" data-toggle="dropdown">
		<div class='user_name'><?= $name; ?></div>
		 <b class="caret"></b>
	</a>
	<ul class="dropdown-menu" role="menu" aria-labelledby="personal_dropdown_1">
		<li role="presentation"><?php echo $this->Html->link('My Dashboard', array('controller' => 'dashboard', 'action' => 'index'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
		<li role="presentation"><?php echo $this->Html->link('My Rentals', array('controller' => 'rentals', 'action' => 'view'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>

		<li role="presentation"><?php echo $this->Html->link('My Account', array('controller' => 'users', 'action' => 'accountinfo'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>

		<li role="presentation"><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
	</ul>
</li>

<!-- Dropdown for user type 2 -->
<li class="personal_menu personal_menu_2 dropdown <?= ($user_type == 2) ? '' : 'hide' ; ?>">
	<a href="#" id="personal_dropdown_2" role="button" class="dropdown-toggle" data-toggle="dropdown">
		<div class='user_name'><?= $name; ?></div>
		 <b class="caret"></b>
	</a>
	<ul class="dropdown-menu" role="menu" aria-labelledby="personal_dropdown_2">
		<li role="presentation"><?php echo $this->Html->link('My Dashboard', array('controller' => 'dashboard', 'action' => 'index'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
		
		<li role="presentation"><?php echo $this->Html->link('Featured Listings', array('controller' => 'FeaturedListings', 'action' => 'index'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>

		<li role="presentation"><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
	</ul>
</li>

