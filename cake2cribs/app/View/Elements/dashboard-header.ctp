<?php
	/* Less files for style */
	/* Eventually switch to css */
	echo $this->Html->css('/less/header.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->css('/less/popover.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->css('/less/slider.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->css('/css/jquery-dialog2/jquery.dialog2.css', array('inline'=>false));
	echo $this->Html->css('/less/checkbox.less?','stylesheet/less', array('inline' => false));
	
	//echo('<link rel="stylesheet" type="text/css" href="/css/jquery-dialog2/jquery.dialog2.css">');
	/* Datepicker and slider css */
	echo $this->Html->css('datepicker');

	/* Datepicker and slider javascript */
	
	// 7/17/13 (Mike) took out the bootstrap-datepicker because it causes naming
	// conflicts with my multidatepicker that I'm using (jquery ui)

	// echo $this->Html->script('bootstrap-datepicker');
	echo $this->Html->script('bootstrap-slider');

if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	echo $this->Html->script('src/PageHeader');
}
?>


<div class="top-bar">
	<!-- <a id="sublet-post" href="#" class="post-button inline pull-left">POST A SUBLET</a> -->
	<!-- <a id="sublet-post" class="post-button inline pull-left open-dialog" href="/users/verifyUniversity"> POST A SUBLET</a> -->
	<a id="subletAddSteps" class="post-button inline pull-left" href="#post-sublet-modal" data-toggle="modal" onclick="A2Cribs.PostSublet.Reset()">POST A SUBLET</a>
	<ul id="left-options" class="inline unstyled pull-left">
		<li class="active"><a href="#">Sublets</a></li>
		<li><a href="#" onclick="A2Cribs.UIManager.Alert('Full-Year Leases are coming soon!');">Full-Year Leases</a></li>
		<li><a href="#" onclick="A2Cribs.UIManager.Alert('Parking is coming soon!');">Parking</a></li>
	</ul>
	<ul id="right-options" class="inline unstyled pull-right">
        <li><a href="/users/logout">Logout</a></li>
		<li><a href="#about-page" data-toggle="modal">About</a></li>
		<li><a href="#contact-page" data-toggle="modal">Contact</a></li>
		<li><a href="#help-page" data-toggle="modal">Help</a></li>
	</ul>
</div>
<div id="header" class="container">
	<a href="/"><div class="main-logo pull-left"></div></a>
	<a href="<?php echo $mapUrl; ?>"><div id="backToMap"></div></a>
	<div id="personal-buttons" class="pull-right">
		<div class="btn-group">
			<a class="btn btn-link hide" data-toggle="dropdown" href="#">
				<img src="/img/head_small.jpg" class="img-polaroid">
				<strong>Jason</strong>
				<span class="caret"></span>
			</a>
			
			<?php if ($this->Session->read('Auth.User.id') == 0)
			{
				echo '<a class="btn btn-link" href="#signupModal" data-toggle="modal">SIGN UP</a>';
				echo '<a class="btn btn-link" href="#myModal" data-toggle="modal">LOGIN</a>';
			}
			else
				echo '<a class="btn btn-link" href="/users/logout">LOGOUT</a>'
			?>
		</div>
	</div>
</div>


<?php
	$this->Js->buffer('
		$(".tooltip-btn").tooltip();	
		$(".popover-btn").popover();
		$(".date-picker").datepicker().on("changeDate", function(ev) {
    		A2Cribs.FilterManager.ApplyFilter(ev);
    	});
		A2Cribs.PageHeader.renderUnreadConversationsCount();
	');
?>

