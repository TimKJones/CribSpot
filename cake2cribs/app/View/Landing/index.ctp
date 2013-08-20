<?php
	echo $this->Html->css('/less/landing.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->script('src/Landing');
	$this->set('title_for_layout', 'Cribspot - Simple and Secure Subletting.');
?>


<?php 
	$this->Js->buffer('
		A2Cribs.Landing.Init(' . json_encode($locations) . ');
		$("#school-form").submit(function() { A2Cribs.Landing.Submit(); return false; });
		$("#search-text").focus();
	');
?>

<?php
	//echo $this->element('login');
	//echo $this->element('post-sublet');
	//echo $this->element('register');
	//echo $this->element('popups');
?>

<?php //echo $this->element('header', array('show_filter' => false, 'show_user' => true)); ?>

<div class="float" id="search-div">
	<img src="/img/landing/logo.png" height="300px" width="300px">
	<h1>Full Year Mother Fucking Listings.</h1>
	<div class="blue-background input-append">
		<form id="school-form">
			<input id="search-text" class="typeahead" placeholder="Search By University or City" type="text" autocomplete="off">
			<button type="submit" id="search-btn" class="btn add-on"><i class="icon-search icon-2x"></i></button>
		</form>
		<?php
		echo $this->Html->link(
			'',
			array('controller' => 'map', 'action' => 'rental'),
			array('class' => 'hide', 'id' => 'sublet-redirect')
		);
		?>

	</div>
	<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
	<a id="subprobs" href="https://twitter.com/intent/tweet?text=This%20%40TheCribspot%20is%20%23idealideal...Please%20bring%20it%20to%20my%20campus%20%23makeithappen">Tweet to bring to your school!</a>
</div>

