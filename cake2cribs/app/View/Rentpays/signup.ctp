<?php

	if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
		echo $this->Html->script('src/Rentpay');
	}
	echo $this->Html->script('https://js.braintreegateway.com/v1/braintree.js');
	echo $this->Html->css('/less/Expert/expert_page.less?v=6','stylesheet/less', array('inline' => false));
	echo $this->element('popups');
	$this->set('title_for_layout', 'Pay Rent | Cribspot');
?>

<div id="expert_page">
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container">

				<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>

				<!-- Be sure to leave the brand out there if you want it shown -->
				<a class="brand" href="/"><img src="/img/expert/logo.png" alt="Cribspot"></a>

				<!-- Everything you want hidden at 940px or less, place within here -->
				<div class="nav-collapse collapse">
					<!-- .nav, .navbar-search, .navbar-form, etc -->
					<ul class="nav pull-right">
						<li><a href="#" class="yellow"><img src="/img/expert/header/review.png" alt="Review" />Review</a></li>
						<li><a href="#about-page" data-toggle="modal"><img src="/img/expert/header/about.png" alt="About" />About</a></li>
						<li><a href="/signup/pm"><img src="/img/expert/header/pm.png" alt="Property Manager" />Property Manager</a></li>
						<li><a href="/login"><img src="/img/expert/header/user.png" alt="User" />User</a></li>
					</ul>
				</div>

			</div>
		</div>
	</div>
	<div class="main">
		<section id="page_1" class="_fullscreen">
			<div class="row-fluid half_height">
				<div class="banner">
					<h1 class="hidden-phone">&nbsp;&nbsp;<em class="bold">Never write a check again.</em><br>Pay rent to any property manager for <em class="bold">free!</em></h1>
				</div>
			</div>
			<div class="row-fluid half_height">
				<div class="span span8 welcome_buttons">
					<div>
						<a href="#rentpay-signup" data-toggle="modal" class="button help">SET UP RENT PAY NOW</a>
						<a href="#top_rentals" class="button">POST SUBLET</a>
					</div>
				</div>
			</div>
		</section>
	</div>
	
<?= $this->element('Rentpay/rentpay-signup'); ?>
<?= $this->element('Rentpay/password-protect'); ?>
</div>
<script type="text/javascript" charset="utf-8">
	window.onload = function()
	{
		w = document.documentElement.scrollWidth;
		winH = document.body.clientHeight;
		setHeight(w, winH);
	};

	window.onresize = function()
	{
		h = document.documentElement.scrollTop;
		w = document.documentElement.scrollWidth;
		winH = document.body.clientHeight;

		setHeight(w, winH);
	};

	setHeight = function(w, winH)
	{
			section = document.querySelectorAll('._fullscreen');
			Array.prototype.forEach.call(section, function(el, i)
			{
				min_height = window.getComputedStyle(el).getPropertyValue("min-height");
				if (min_height.indexOf("vh") === -1)
					el.style.height = winH + "px";
			});
	};

	$('a[href*=#]:not([href=#])').click(function(event) {
		console.log(event);
		var href = event.toElement.href;
		var href = $(event.currentTarget).attr('href');
		if(href.indexOf("#") != -1 && $(href).is(":visible"))
		{
			$('html,body').animate({
				scrollTop: $(href).position().top
			}, 500);
			return false;
		}
	});
</script>
