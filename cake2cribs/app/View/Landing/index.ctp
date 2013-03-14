<?php
	echo $this->Html->css('/less/landing.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->script('src/Landing');
?>


<?php 
	$this->Js->buffer('
		A2Cribs.Landing.Init(' . json_encode($locations) . ');
		$("#search-form").submit(function() { A2Cribs.Landing.Submit(); return false; });
	');
?>

<div class="top-bar angled">
	<ul id="right-options" class="inline unstyled pull-right">
		<li><a href="about.php">About</a></li>
		<li><a href="#contact">Contact</a></li>
		<li><a href="#help">Help</a></li> <!-- email popup to help -->
		<li><a href="#more">More</a></li> <!-- terms and conditions -->
	</ul>
</div>
<div id="landing-header">
	<div id="logo">CRIBSPOT</div>
</div>
<div id="photo-background">
	<table>
		<tr>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house2.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house2.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house2.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
		</tr>
		<tr>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house2.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house2.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house2.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
		</tr>
		<tr>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house2.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
		</tr>
		<tr>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
			<td><div class="img-ratio img-faded" style="background-image:url(/img/landing/house1.jpg)"></div></td>
		</tr>								
	</table>
</div>
<div class="float" id="search-div">
	<h2>WHERE TO LIVE?</h2>
	<div id="search" class="input-append">
		<form id="search-form">
			<input id="search-text" class="typeahead" type="text" autocomplete="off">
			<a href="" onclick="A2Cribs.Landing.Submit()" id="search-btn" class="btn add-on"><i class="icon-search icon-large"></i></a>
		</form>

	</div>
	<div class="btn-group" data-toggle="buttons-radio">
		<button type="button" class="btn btn-primary active">Sublets</button>
		<button type="button" class="btn btn-primary">Full-Year Leases</button>
		<button type="button" class="btn btn-primary">Parking</button>
	</div>
</div>

<div id="landing-footer">
	<div id="slogan">Live your Life #YOLO</div>
</div>
