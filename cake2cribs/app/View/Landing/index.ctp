<?php
	echo $this->Html->css('/less/landing.less?','stylesheet/less', array('inline' => false));
	//echo $this->Html->script('jquery-ui-autocomplete');
	//echo $this->Html->script('jquery.select-to-autocomplete.min.js');
	echo $this->Html->script('src/Landing');
?>

<?php echo '<script> var locationObjects=' . json_encode($locations) . ';</script>'; ?>

<script>

var locations = Array();
for (var i = 0; i < locationObjects.length; i++)
{
	locations.push(locationObjects[i].School.school_name);
	locations.push(locationObjects[i].School.city);
}

locations.sort();
  $(function() {
    $( ".typeahead" ).typeahead({
      source: locations
    });
  });
</script>


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
		<form action="javascript:A2Cribs.Landing.Submit()">
			<input id="search-text" class="typeahead" type="text">
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


<!--

OLD VERSION 
NEED TO WORK ON A FEW THINGS BEFORE
	CHANGED IN CONTROLLER
	ALL PHP AND JS DISABLED

<?php
/*
echo $this->Html->script('jquery-ui-autocomplete');
echo $this->Html->script('jquery.select-to-autocomplete.min.js');
echo $this->Html->script('src/Landing');
echo $this->Html->css('landing');
*/
?>
<? /*php echo '<script> var locationObjects=' . json_encode($locations) . ';</script>'; */?>
<script>
/*
var locations = Array();
for (var i = 0; i < locationObjects.length; i++)
{
	locations.push(locationObjects[i].School.school_name);
	locations.push(locationObjects[i].School.city);
}

locations.sort();
  $(function() {
    $( "#locations" ).autocomplete({
      source: locations
    });
  });
*/
</script>
<script type="text/javascript" charset="utf-8">
/*jQuery.fn.extend({
 propAttr: $.fn.prop || $.fn.attr
});
	  (function($){
	    $(function(){
	      $('select').selectToAutocomplete();
	      $('form').submit(function(){
	        alert( $(this).serialize() );
	        return false;
	      });
	    });
	  })(jQuery);*/
	</script>
</head>
<body>
<div class="ui-widget">
  <label for="locations"></label>
  <input id="locations" placeholder=" Search for your School or City" />
  <button id="locationSubmit" onclick="A2Cribs.Landing.Submit()">Submit</button>
</div>
</body>
</html>

<script>
/*$(document).ready(function(){
  $('.ui-autocomplete-input').css('width','430px');
});*/
</script>
-->