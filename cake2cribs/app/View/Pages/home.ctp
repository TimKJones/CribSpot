<?php
	echo $this->Html->css('/less/Expert/expert_page.less?v=5','stylesheet/less', array('inline' => false));
	echo $this->element('popups');
	$this->set('title_for_layout', 'Local Experts | Cribspot');
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
					<h1 class="hidden-phone">&nbsp;&nbsp;A housing site built by <em class="bold">THOUSANDS</em> of renters.<br>Get free advice from a local renter expert now.</h1>
				</div>
			</div>
			<div class="row-fluid half_height">
				<div class="span span8 welcome_buttons">
					<div>
						<a href="#expert-contact" data-toggle="modal" class="button help">HELP ME FIND A RENTAL</a>
						<a href="#top_rentals" class="button">SEE TOP RENTALS</a>
					</div>
				</div>
				<div class="span span4 text-center visible-desktop">
					<div class="expert-region visible-desktop">
						<img src="/img/founders/indiana.png" alt="Campus Expert" />
						<p class="name">Alex Gross</p>
						<p class="description">Hill Street Area Expert</p>
						<p class="message f18">See what an expert can help you with</p>
						<a class="expert_info_btn" href="#expert_info"><i class="icon-chevron-down"></i></a>
					</div>
				</div>
			</div>
		</section>
		<section id="expert_info" class="_fullscreen">
			<div class="blue-top row-fluid">
			</div>
			<div class="row-fluid">
				<div class="span3 hidden-phone">
					<a class="brand" href="/"><img src="/img/expert/logo_black.png" alt="Cribspot"></a>
				</div>
				<div class="span9 text-center">
					<div class="rental_bubble">
						<p class="bold">Finding a rental is hard. Let us help.</p>
						<p>Real people that will help you find your place.</p>
						<div class="arrow-down"></div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span2 visible-desktop"></div>
				<div class="span span6 features">
					<div class="row-fluid">
						<div class="feature">
							<img src="/img/expert/expert.png" alt="Expert" />
							<div>
								<p class="title">HEAR OUR EXPERT OPINION</p>
								<p>New to the area? We can find you a place that suits your style.</p>
							</div>
						</div>
					</div>
						<div class="row-fluid">
						<div class="feature">
							<img src="/img/expert/video.png" alt="Video Tours" />
							<div>
								<p class="title">SEE PHOTOS, VIDEO TOURS &amp; REVIEWS</p>
								<p>We've got premium content so you'll know exactly what you're getting into.</p>
							</div>
						</div>
					</div>
						<div class="row-fluid">
						<div class="feature">
							<img src="/img/expert/pin.png" alt="Neighborhood insights" />
							<div>
								<p class="title">GET NEIGHBORHOOD INSIGHTS</p>
								<p>We'll give the lowdown on every bar, block, and apartment building.</p>
							</div>
						</div>
					</div>
						<div class="row-fluid">
						<div class="feature last-feature">
							<img src="/img/expert/sign.png" alt="Lease Signing" />
							<div>
								<p class="title">EXPEDITE YOUR LEASE SIGNING</p>
								<p>We're with you at every step to make the leasing process as simple and stress-free as possible.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="span span4 visible-desktop">
					<div class="row-fluid text-center">
						<span class="mission f24 gray">- Some of Our Local Experts -</span>
					</div>
					<div class="row-fluid text-center">
						<div class="local-expert-container">
							<div class="local-expert">
								<img src="/img/expert/locals/joe.jpg" alt="Campus Expert" />
								<div class="expert-description">
									<span class="mission f18">Joe,</span>&nbsp;&nbsp;<span class="gotham-bold f12">Polsci 2015</span>
								</div>
							</div>
						</div>
						<div class="local-expert-container">
							<div class="local-expert">
								<img src="/img/expert/locals/jason.jpg" alt="Campus Expert" />
								<div class="expert-description">
									<span class="mission f18">Jason,</span>&nbsp;&nbsp;<span class="gotham-bold f12">Ross 2013</span>
								</div>
							</div>
						</div>
					</div>
					<div class="row-fluid text-center">
						<div class="local-expert-container">
							<div class="local-expert">
								<img src="/img/expert/locals/tim.jpg" alt="Campus Expert" />
								<div class="expert-description">
									<span class="mission f18">Tim,</span>&nbsp;&nbsp;<span class="gotham-bold f12">CSE 2013</span>
								</div>
							</div>
						</div>
						<div class="local-expert-container">
							<div class="local-expert">
								<img src="/img/expert/locals/mattmcclain.jpg" alt="Campus Expert" />
								<div class="expert-description">
									<span class="mission f18">Matt,</span>&nbsp;&nbsp;<span class="gotham-bold f12">Grad 2014</span>
								</div>
							</div>
						</div>
					</div>
					<div class="row-fluid text-center">
						<div class="local-expert-container">
							<div class="local-expert">
								<img src="/img/expert/locals/tyler.jpg" alt="Campus Expert" />
								<div class="expert-description">
									<span class="mission f18">Tyler,</span>&nbsp;&nbsp;<span class="gotham-bold f12">Econ 2014</span>
								</div>
							</div>
						</div>
						<div class="local-expert-container">
							<div class="local-expert">
								<img src="/img/expert/locals/mahraan.jpg" alt="Campus Expert" />
								<div class="expert-description">
									<span class="mission f18">Mahraan,</span>&nbsp;&nbsp;<span class="gotham-bold f12">2015</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<a href="#top_rentals" class="see_top_rentals f18">
				SEE TOP RENTALS
				<div class="arrow-down"></div>
			</a>
			
		</section>
		<section id="top_rentals" class="_fullscreen">
			<div class="row-fluid text-center">
				<p class="mission f70 orange">- Ann Arbor -</p>
				<p class="gotham-bold white f45">FEATURED LISTINGS</p>
				<p class="gotham-bold dark-gray f30">THESE PLACES OFFER <span class="f40 green">$100</span> WHEN SIGNING</p>
			</div>
			<div class="row-fluid relative">
				<div class="house_separator"></div>
				<img id="house_symbol" src="/img/expert/house_symbol.png" alt="House banner" />
				<div class="house_separator"></div>
			</div>
			<div class="row-fluid listings_row">
				<div class="span4">
					<div class="feature_img">
						<img src="/img/expert/featured_listing_1.jpg" alt="The Varsity" />
						<div class="hover_div text-center"><a class="button help" href="/listing/2617/">I'M INTERESTED</a></div>
					</div>
					<div class="featured_description">
						<table>
							<tr>
								<td>
									<div class="listing">
										<span class="pull-left green f20">$1,700+</span>
										<span class="pull-right dark-gray"><span class="f20">1</span><span class="f18"> bed</span></span>
									</div>
									<div class="listing">
										<span class="pull-left green f20">$1,485+</span>
										<span class="pull-right dark-gray"><span class="f20">2</span><span class="f18"> bed</span></span>
									</div>
									<div class="listing">
										<span class="pull-left green f20">$1,045+</span>
										<span class="pull-right dark-gray"><span class="f20">4</span><span class="f18"> bed</span></span>
									</div>
								</td>
								<td class="gotham-thin f14">Luxury apartments with great location, fitness center, rooftop sky lounge, all utilities, and much more.</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="span4">
					<div class="feature_img">
						<img src="/img/expert/featured_listing_2.jpg" alt="Ann Arbor City Apartments" />
						<div class="hover_div text-center"><a class="button help" href="/listing/12815">I'M INTERESTED</a></div>
					</div>
					<div class="featured_description">
						<table>
							<tr>
								<td>
									<div class="listing">
										<span class="pull-left green f20">$1,390+</span>
										<span class="pull-right dark-gray"><span class="f20"></span><span class="f18">Studio</span></span>
									</div>
									<div class="listing">
										<span class="pull-left green f20">$1,525+</span>
										<span class="pull-right dark-gray"><span class="f20">1</span><span class="f18"> bed</span></span>
									</div>
									<div class="listing">
										<span class="pull-left green f20">$1,880+</span>
										<span class="pull-right dark-gray"><span class="f20">2</span><span class="f18"> bed</span></span>
									</div>
								</td>
								<td class="gotham-thin f14">An extraordinary experience. Enjoy the Sky Park, large Clubroom, and superb location among many great features.</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="span4">
					<div class="feature_img">
						<img src="/img/listings/lrg_52f052459d813.jpg" alt="Signature Club Apartments" />
						<div class="hover_div text-center"><a class="button help" href="/listing/12801/">I'M INTERESTED</a></div>
					</div>
					<div class="featured_description">
						<table>
							<tr>
								<td>
									<div class="listing">
										<span class="pull-left green f20">$1,073</span>
										<span class="pull-right dark-gray"><span class="f20">1</span><span class="f18"> bed</span></span>
									</div>
									<div class="listing">
										<span class="pull-left green f20">$1,173</span>
										<span class="pull-right dark-gray"><span class="f20">2</span><span class="f18"> bed</span></span>
									</div>
									<div class="listing">
										<span class="pull-left green f20">$1,351</span>
										<span class="pull-right dark-gray"><span class="f20">2</span><span class="f18"> bed</span></span>
									</div>
								</td>
								<td class="gotham-thin f14">Clean, pleasant feeling apartments with pool, fitness center, large rooms, serene surroundings, pet friendly</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</section>
		<section id="gap">
		</section>
	</div>
	<?= $this->element('expert_contact'); ?>
	<?= $this->element('expert_signup'); ?>
	
	<footer class="text-center">
		<p><span>Rent</span> any of the <span>11,182 rentals</span> on Cribspot with a <span>local expert now</span></p>
		<a class="button help" href="/rental/University_of_Michigan-Ann_Arbor">SEE MORE RENTALS</a>
	</footer>
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
