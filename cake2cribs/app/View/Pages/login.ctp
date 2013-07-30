<?php echo $this->Facebook->html(); ?>
	<?php echo $this->Facebook->init(); ?>
	<?php echo $this->Facebook->login(array('perms' => 'email,publish_stream')); ?>
	<?php echo $this->Facebook->logout(array('label' => 'Logout', 'redirect' => array('controller' => 'landing', 'action' => 'index'))); ?>
</html>