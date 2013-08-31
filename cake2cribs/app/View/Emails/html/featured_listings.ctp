<style>
    .ui-helper-center {
        text-align: center;
        padding: 5px;
    }
</style>

<div id = 'listings-wrapper'>

    <?php 
        foreach($listings as $day=>$listingsofday){
            echo "<h3>Featured Listings for $day</h3>"?>
            <table>
                <tr>
                    <th>Listing ID</th>
                    <th>Address</th>
                    <th>Beds</th>
                    <th>Baths</th>
                    <th>Rent</th>
                    <th>Highlights</th>
                    <th>Contact Email</th>
                    <th>Contact Phone</th>
                    <th>Listing URL</th>
                </tr>
<?php
$fields = array('listing_id', 'address', 'beds', 'baths', 'rent', 'highlights', 'contact_email', 'contact_phone',
    'listing_url');

?>
            <?php foreach ($listingsofday as $listing) {
                echo "<tr>";
                foreach ($fields as $field){
                    echo "<td style = 'padding: 5px; text-align:center;'>";
                    if (!empty($listing[$field]))
                        echo $listing[$field];
                    echo "</td style = 'padding: 5px; text-align:center;'>";
                }

                echo "</tr>";   
            }
            echo "</table><br>";
        }