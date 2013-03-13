<?php
	/* Use this to fetch individual css */
	//echo $this->Html->css('index', null, array('inline' => false));
	echo $this->Html->css('/less/sidebar.less?','stylesheet/less', array('inline' => false));
?>

<div class="side-bar">
	<div style="font-size:13px"><strong>Showing Most Recently Posted Based on Filters:</strong></div>
	<div class="filtered-listings-container">
		<ul class="unstyled">
			<li class="filtered-listing">
				<div class="filt-left">
					<div class="filt-price">$600</div>
					<div class="filt-type">APART.</div>
				</div>
				<div class="filt-middle">
					<div class="filt-name">1 <small>BR</small> - Zaragon Place</div>
					<div class="filt-desc">Large room w/ AC + parking</div>
				</div>
				<div class="filt-right">
					<div><div class="filt-friends">3</div><i class="icon-facebook-sign icon-large"></i></div>
				</div>
			</li>
			<li class="filtered-listing">
				<div class="filt-left">
					<div class="filt-price">$1600</div>
					<div class="filt-type">HOUSE</div>
				</div>
				<div class="filt-middle">
					<div class="filt-name">1 <small>BR</small> - Zaragon Place</div>
					<div class="filt-desc">Large room w/ AC + parking</div>
				</div>
				<div class="filt-right">
					<div><div class="filt-friends">123</div><i class="icon-facebook-sign icon-large"></i></div>
				</div>
			</li>
			<li class="filtered-listing">
				<div class="filt-left">
					<div class="filt-price">$1600</div>
					<div class="filt-type">HOUSE</div>
				</div>
				<div class="filt-middle">
					<div class="filt-name">1 <small>BR</small> - Zaragon Place</div>
					<div class="filt-desc">Large room w/ AC + parking</div>
				</div>
				<div class="filt-right">
					<div><div class="filt-friends">123</div><i class="icon-facebook-sign icon-large"></i></div>
				</div>
			</li>
		</ul>
	</div>
</div> <!-- End of Side bar -->