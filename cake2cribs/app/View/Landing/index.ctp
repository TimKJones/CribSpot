<?php
echo $this->Html->script('jquery-ui-autocomplete');
echo $this->Html->script('jquery.select-to-autocomplete.min.js');
echo $this->Html->script('src/Landing');
echo $this->Html->css('landing');
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
    $( "#locations" ).autocomplete({
      source: locations
    });
  });
</script>
<script type="text/javascript" charset="utf-8">
jQuery.fn.extend({
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
	  })(jQuery);
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
$(document).ready(function(){
  $('.ui-autocomplete-input').css('width','430px');
});
</script>