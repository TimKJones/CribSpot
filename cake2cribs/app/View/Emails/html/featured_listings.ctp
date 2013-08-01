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
                    <th>Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip</th>
                    <th>Beds</th>
                    <th>Baths</th>
                    <th>Rent</th>
                    <th>Highlights</th>
                    <th>Description</th>
                    <th>Contact Email</th>
                    <th>Contact Phone</th>
                </tr>

            <?php foreach ($listingsofday as $listing) {
                echo "<tr>";
                echo "<td style = 'padding: 5px; text-align:center;'>$listing[address]</td style = 'padding: 5px; text-align:center;'>";
                echo "<td style = 'padding: 5px; text-align:center;'>$listing[city]</td style = 'padding: 5px; text-align:center;'>";    
                echo "<td style = 'padding: 5px; text-align:center;'>$listing[state]</td style = 'padding: 5px; text-align:center;'>";
                echo "<td style = 'padding: 5px; text-align:center;'>$listing[zip]</td style = 'padding: 5px; text-align:center;'>";
                echo "<td style = 'padding: 5px; text-align:center;'>$listing[beds]</td style = 'padding: 5px; text-align:center;'>";
                echo "<td style = 'padding: 5px; text-align:center;'>$listing[baths]</td style = 'padding: 5px; text-align:center;'>";
                echo "<td style = 'padding: 5px; text-align:center;'>$$listing[rent]</td style = 'padding: 5px; text-align:center;'>";
                echo "<td style = 'padding: 5px; text-align:center;'>$listing[highlights]</td style = 'padding: 5px; text-align:center;'>";
                echo "<td style = 'padding: 5px; text-align:center;'>$listing[description]</td style = 'padding: 5px; text-align:center;'>";
                echo "<td style = 'padding: 5px; text-align:center;'>$listing[contact_email]</td style = 'padding: 5px; text-align:center;'>";
                echo "<td style = 'padding: 5px; text-align:center;'>$listing[contact_phone]</td style = 'padding: 5px; text-align:center;'>";

                echo "</tr>";   
            }
            echo "</table><br>";
        }