<?php
	/* Less files for style */
	/* Eventually switch to css */
	echo $this->Html->css('/less/header.less?','stylesheet/less', array('inline' => false));

	echo $this->Html->script('src/PageHeader');

?>


<div class="top-bar">
	<ul id="right-options" class="inline unstyled pull-right">
		<li><a href="#about">About</a></li>
		<li><a href="#partners">Partners</a></li>
		<li><a href="#contact">Contact</a></li>
		<li><a href="#help">Help</a></li>
		<li><a href="#more">More</a></li>
	</ul>
</div>
<div id="header" class="container">
	<a href="/"><div class="main-logo pull-left"></div></a>
</div>
