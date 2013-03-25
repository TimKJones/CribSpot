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

<?= $this->element('header-basic'); ?>
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
		<form id="school-form">
			<input id="search-text" class="typeahead" type="text" autocomplete="off">
			<button type="submit" id="search-btn" class="btn add-on"><i class="icon-search icon-large"></i></button>
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
