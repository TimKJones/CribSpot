<?= $this->Html->css('/less/click-bubble.less?','stylesheet/less', array('inline' => false)); ?>

<div class="click-bubble multi-listing">
	<div class="multi-content">
		<i class="icon-map-marker icon-large"></i><i class="sublet-name">Willow Tree Apartments</i>
		<div class="inline-block"><small>Listings: </small><div id="listing-count">5</div></div>
	</div>
	<a class="close-button" href="#" onclick="A2Cribs.Map.ClickBubble.Close();">X</a>

	<div class="listings">
	</div>

	<div class="listing-block hide">
		<div class="inline-block">
			<a href="#"><img class="listing-image" src="/img/landing/house1.jpg" alt="C-House"></a>
		</div>
		<div class="click-bubble-data">
			<i class="single-content"><i class="icon-map-marker icon-large"></i><i class="sublet-name">Willow Tree Apartments</i><br></i>
			<i class="icon-user"></i><i class="username bold-text">Michelle</i>
			<i class="fb-mutual"><i class="icon-facebook-sign"></i><i class="bold-text friend-count">132</i></i>
			<i class="hide"><i class="icon-time"></i><i id="posted-time">2 days ago</i></i>
			<br>
			<i class="icon-calendar"></i><i class="date-range bold-text">May 12-Aug 31</i><small> (Flexible)</small>
			<br>
			<i class="icon-money-sign">$</i><i class="bed-price bold-text">1050</i><small> /m</small>
			<img src="/img/tooltip/bed_icon.png" alt="bed" width="24" height="12" align="middle"><i class="bed-count bold-text">3</i>
			<br>
			<i class="icon-building"></i><i class="building-type bold-text">House</i>
			<div class="extended-buttons">
				<a href="#" class="listing-popup-link" title="Detailed View"><i class="icon-plus"></i></a>
				<a href="#" class="listing-message" title="Message this user"><i class="icon-comments"></i></a>
				<a href="#" class="favorite-clickable"><i class="icon-heart"></i></a>
			</div>
		</div>
	</div>	
</div>

