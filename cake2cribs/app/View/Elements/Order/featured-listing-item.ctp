<?php 

    $address = $listing['Marker']['street_address'];
    $listing_id = $listing['Listing']['listing_id'];
?>

<div class = 'featured-listing-order-item' id = '<?php echo $listing_id; ?>'>
    <p class = 'address'><?php echo $address; ?></p>
    <div class = 'date-range'>
        Start: <input type = 'text' class = 'date-input start'></input>
        End: <input type = 'text' class = 'date-input end'></input>
    </div>
    <span class = 'pull-right pricing' style = 'display:none'>
        <Strong>Weekdays: </strong><span class = 'weekdays'></span>
        <Strong>   Weekends: </strong><span class = 'weekends'></span>
        <strong>   Price:</strong><span class = 'price'></span>
    </span>
</div>