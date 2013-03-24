<div id="slider-content">
	<div id="price-min" class="slider-value">$0</div>
	<input type="text" data-slider-min="0" data-slider-max="2000" data-slider-step="100" data-slider-value="[0,2000]" id="slider">
	<div id="price-max" class="slider-value">$2,000+</div>
</div>

<?php
	$this->Js->buffer('
		$("#slider").slider()
		.on("slide", function(ev){
			var max = ev.value[1]
				, plus = (max === 2000)? "+" : ""
			$("#price-min").html("$" + ev.value[0].toString().replace(/\B(?=(?:\d{3})+(?!\d))/g, ","))
			$("#price-max").html("$" + max.toString().replace(/\B(?=(?:\d{3})+(?!\d))/g, ",") + plus)
			$("#price-filter").val($("#price-min").html() + " - " + $("#price-max").html())
		});
		$("#slider").slider()
		.on("slideStop", function(ev){
			A2Cribs.FilterManager.ApplyFilter(ev);
		});
	');
?>