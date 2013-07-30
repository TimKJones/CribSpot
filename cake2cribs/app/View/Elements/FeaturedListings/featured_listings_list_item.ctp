<?php 
    if($listing['min_occup'] == null){
        $occup = $listing['max_occup'];
    }else{
        $occup = $listing['min_occup']. "-" .$listing['max_occup'];
    }
?>

<div class = 'map-listing-item'>
    <div class = 'property-picture'>
        <img src = '/img/linder_house.jpg'></img>    
    </div>
    
    <div class = 'listing-info'>
        <div class = 'top-section'>
            <div class = 'left'>
                <strong><?php echo $listing['address'];?></strong>
                <span class = 'favorite-listing icon-stack'>
                    <i class = 'icon-circle-blank icon-stack-base'></i>
                    <i class = 'icon-heart'></i>
                </span>
            </div>
            <div class = 'right'>
                <span class = 'price'><?php echo "$".$listing['rent']; ?></span>
                <div class = 'bottom-border'></div>
            </div>
        </div>

        <div class = 'bottom-section'>
            <span class = 'listing-type'><i class = 'icon-home'></i> House</span>
            <span class = 'num-occupants'><i class = 'icon-group'></i>  <?php echo $occup; ?></span>
            <span class = 'lease-type'><i class = 'icon-calendar'></i> <?php echo $listing['start_month']."/".$listing['end_month'];?></span>
            <span class = 'lease-duration'><i class = 'icon-time'></i> <?php echo $listing['num_months']." mo";?></span>
        </div>

    </div>

</div>