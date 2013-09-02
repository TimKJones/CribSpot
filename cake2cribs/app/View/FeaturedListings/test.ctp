<?php $this->Html->css('/less/featured-listings.less?','stylesheet/less', array('inline' => false)); ?>

<?php 
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
    echo $this->Html->script('src/FeaturedListings');
    echo $this->Html->script('less.js');
}
?>

<html>

<div class = 'container'>

<div class = 'row'>
    <div class = 'span6'>
        <div class = 'test-controls'>
            <h2>Find Featured listings</h2>
            <form id = 'loc-input' class = 'form-horizontal'>
                <input type = 'text' name = 'up_lat' placeholder = 'up_lat' value = '100'></input><br>
                <input type = 'text' name = 'low_lat' placeholder = 'low_lat' value = '-100'></input> <br>
                <input type = 'text' name = 'up_long' placeholder = 'up_long' value = '100'></input><br>
                <input type = 'text' name = 'low_long' placeholder = 'low_long' value = '-100'></input><br>
                <br>
                <button class = 'btn' id = 'findListings'>Find</button>
            </form>
        </div>
      
    </div>

    

    <div class = 'span6'>
        <div id = 'widget' class = 'fl_sidebar'>
            <div class = 'listings_list'>
            </div>
        </div>
    </div>
</div>
<script>
    
$(function(){
    
    var widget = new A2Cribs.FeaturedListings($('#widget'));
   

    $("#findListings").click(function(event){
        event.preventDefault();
        var d = $('#loc-input').serializeArray();

        widget.find(d[0].value, d[1].value, d[2].value, d[3].value);

        return false;
    });
})

// $("#findListings").click(function( event ) {
//   event.preventDefault();

// );
// });

</script>

</html>