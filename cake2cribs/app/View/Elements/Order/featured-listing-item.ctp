<?php 

    $address = $listing['Marker']['street_address'];
    $listing_id = $listing['Listing']['listing_id'];
?>

<div class = 'featured-listing-order-item' id = '<?php echo $listing_id; ?>'>
    <div class = ''>
        <div class = ''>
            <span class = 'address'><?php echo $address; ?></span>
        </div>
    </div>
    <p>Add Dates:</p>
    <div class = 'mdp'>
    </div>
    <table class = 'total-tally'>
            <tr>
                <td>Weekdays:</td>
                <td><strong class = 'weekdays'>0</strong><strong> x <?php echo "$". $rules['FeaturedListings']['costs']['weekday'];?></strong></td>
            </tr>
            <tr>
                <td>Weekends:</td>
                <td><strong class = 'weekends'>0</strong><strong> x <?php echo "$". $rules['FeaturedListings']['costs']['weekend'];?></strong></td>
            </tr>
            <tr>
                <td><strong>Price: </td>
                <td></strong><span class = 'price'>$0</span></td>
            </tr>
    </table>
<!--     <div class = 'row-fluid'>
        
        <div class = 'span12'>
            
            
        </div>

    </div> -->

    <!-- <span class = 'pull-right pricing' style = 'display:none'>
        <Strong>Weekdays: </strong><span class = 'weekdays'></span>
        <Strong>   Weekends: </strong><span class = 'weekends'></span>
        <strong>   Price:</strong><span class = 'price'></span>
    </span> -->
</div>


<!-- 
 <div class = 'date-range row-fluid'>
        <div class = 'span8'>
            Start: <input type = 'text' class = 'date-input start'></input>
            End: <input type = 'text' class = 'date-input end'></input> 
        </div>
        <div class 'span3 pricing'>        
            <small> Weekdays: <span class = 'weekdays'></span></small><br>
            <small> Weekends: <span class = 'weekends'></span></small><br>
            <small> Price: <span class = 'price'></span></small>
            <a href = '#' class ='remove-range'><i class = 'pull-right icon-trash icon-large'></i></a>
        </div>

    </div> -->