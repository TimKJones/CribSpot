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
	// echo $this->Html->script('bootstrap-datepicker');
	echo $this->Html->script('bootstrap-slider');
	echo $this->Html->script('src/PageHeader');
?>
<div id="header" class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container" style="width: auto;">
			<a class="header_logo" href="#"><img src="/img/header/logo.png"></a>
			<ul class="nav pull-right">
				<?php if (isset($show_user) && $show_user) { /* Next step is to check if logged in */ 
					if ($this->Session->read('Auth.User.id') != 0) { ?>
					<li class="personal_menu dropdown">
						<?
						$pic_url = "/img/head_large.jpg";
						if($AuthUser['facebook_userid'])
							$pic_url = "https://graph.facebook.com/".$AuthUser['facebook_userid']."/picture?width=80&height=80";
						?>
						<a href="#" id="personal_dropdown" role="button" class="dropdown-toggle" data-toggle="dropdown"><img src="<?= $pic_url ?>"><?= $AuthUser['first_name'] ?> <b class="caret"></b></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="personal_dropdown">
							<li role="presentation"><?php echo $this->Html->link('My Dashboard', array('controller' => 'dashboard', 'action' => 'index'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
							<li role="presentation"><?php echo $this->Html->link('My Rentals', array('controller' => 'rentals', 'action' => 'view'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
							<li role="presentation"><?php echo $this->Html->link('My Account', array('controller' => 'users', 'action' => 'accountinfo'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
							<li role="presentation"><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
						</ul>
					</li>
					<li class="personal_buttons">
						<a href=""><i class="icon-comment"></i></a>
						<a href=""><i class="icon-heart"></i></a>
					</li>
				<?php } 
				} ?>
				<li class="menu dropdown">
					<a href="#" id="menu_dropdown" role="button" class="dropdown-toggle" data-toggle="dropdown">Menu <b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="menu_dropdown">
						<li role="presentation"><a role="menuitem" tabindex="-1" href="#">About</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="http://blog.cribspot.com" target="_blank">Blog</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Contact</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Help</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>

<?php
/*	$this->Js->buffer('
		$(".tooltip-btn").tooltip();	
		$(".popover-btn").popover();
		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		A2Cribs.FilterManager.StartDateObject = $("#startDate").datepicker({
		  onRender: function(date) {
		    return date.valueOf() < now.valueOf() ? "disabled" : "";
		  }
		}).on("changeDate", function(ev) {
    		A2Cribs.FilterManager.ApplyFilter(ev);
    	}).data("datepicker");

		A2Cribs.FilterManager.EndDateObject = $("#endDate").datepicker({
		  onRender: function(date) {
		    return date.valueOf() < now.valueOf() ? "disabled" : "";
		  }
		}).on("changeDate", function(ev) {
    		A2Cribs.FilterManager.ApplyFilter(ev);
    	}).data("datepicker");
		A2Cribs.PageHeader.renderUnreadConversationsCount();
		$("#search-form").submit(function() { A2Cribs.FilterManager.SearchForAddress(); return false; });
	');
	*/
?>
