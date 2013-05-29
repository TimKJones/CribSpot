
<?php //echo $this->Html->script('src/FeaturedListings'); ?>
<html>

<div class = 'form-horizontal'>
    <input type = 'text' name = 'up_lat' placeholder = 'up_lat'></input><br>
    <input type = 'text' name = 'low_lat' placeholder = 'low_lat'></input> <br>
    <input type = 'text' name = 'up_long' placeholder = 'up_long'></input><br>
    <input type = 'text' name = 'low_long' placeholder = 'low_long'></input><br>
    <button id = 'food'></button>
</div>

<div class = 'fl_sidebar'>
    <div id = 'listings_list'>
        
    </div>
</div>

<script>
    
$("#food" ).click(function( event ) {
  event.preventDefault();
  var queryparms = $(this).serialize();
  var url = '/FeaturedListings/getListings' + queryparms;
  $('#listings_list').load(url, function(response){console.log(response);});
  return false;
);
});

</script>

</html>