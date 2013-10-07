<style>
.ui-helper-center {
    text-align: center;
    padding: 5px;
}
#company_name{
    text-align: center;
}

#hor-minimalist-b
{
    font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
    font-size: 12px;
    background: #fff;
    margin: 45px;
    width: 550px;
    border-collapse: collapse;
    text-align: left;
}
#hor-minimalist-b th
{
    font-size: 14px;
    font-weight: normal;
    color: #039;
    padding: 10px 8px;
    border-bottom: 2px solid #6678b1;
}
#hor-minimalist-b td
{
    border-bottom: 1px solid #ccc;
    color: #669;
    padding: 6px 8px;
}

</style>

<div id = 'report-wrapper'>

<?php 
        $metricsTitlesMap = array(
            'ListingClicks' => 'Times Your Rentals Were Viewed:' ,
            'WebsiteReferrals' => 'Referrals to Your Website:',
            'PhoneCalls' => 'Phone Calls from Cribspot:',
            'Messages' => 'Messages Sent to You:'
        );
        $thStyle = 'font-size: 14px;font-weight: normal;color: #039;padding: 10px 8px;border-bottom: 2px solid #6678b1;';
        $tdStyle = 'border-bottom: 1px solid #ccc;color: #669;padding: 6px 8px;';
/****** DAILY METRICS REPORT ******/
    if (array_key_exists('company_name', $user))
        echo "<h3 id='company_name' style='text-align: center;'>" . $user['company_name'] ." - ".$timePeriod." Cribspot Metrics Report</h3>";

echo "<p>This is a report of the exposure your properties received this week on <a href='www.cribspot.com'>Cribspot</a>.  If you have questions about this report or would like further information, let us know by responding to this email.</p>"
?>
<body style='line-height: 1.6em;'>
    <table id="hor-minimalist-b" style='font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;font-size: 12px;background: #fff;margin: 15px;width: 550px;border-collapse: collapse;text-align: left;' summary="Daily Metrics Report">
        <thead>
            <tr>
                <th scope='col' style='<?php echo $thStyle?>'>Overview</th>
<?php
if ($timePeriod === 'Weekly'){ ?>
            <th scope='col' style='<?php echo $thStyle?>'>Last Week</th>
<?php } else { ?>
            <th scope='col' style='<?php echo $thStyle?>'>Yesterday</th>
<?php } ?>
                <th scope='col' style='<?php echo $thStyle?>'>Total*</th>
            </tr>
        </thead>
        <tbody>
<?php 
    foreach($metricsTitlesMap as $metric=>$title) {
        echo "<tr>";
        /* ROW TITLE */
        echo "<td style='<?php echo $tdStyle ?>' >";
        echo $title;
        echo "</td>";
        /* DAILY */
        echo "<td style='<?php echo $tdStyle ?>'>";
        if (array_key_exists('daily' . $metric, $overviewMetrics))
            echo $overviewMetrics['daily' . $metric];
        echo "</td>";
        /* TOTAL */
        echo "<td style='<?php echo $tdStyle ?>'>";
        if (array_key_exists('total' . $metric, $overviewMetrics))
            echo $overviewMetrics['total' . $metric];
        echo "</td>";

        echo "</tr>"; 
    }
        echo '</tbody>';
        echo "</table>";
?>

<?php /****** Most Viewed Rentals Today ******/ ?>
<table id="hor-minimalist-b" style='font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;font-size: 12px;background: #fff;margin: 15px;width: 550px;border-collapse: collapse;text-align: left;' summary="Your Most Viewed Rentals">
        <thead>
            <tr>
                <th scope='col' style='<?php echo $thStyle?>'>Your Most Viewed Rentals<br/> Still Available - View Count:</th>
<?php
if ($timePeriod === 'Weekly'){ ?>
            <th scope='col' style='<?php echo $thStyle?>'>Last Week<br/></th>
<?php } else { ?>
            <th scope='col' style='<?php echo $thStyle?>'>Yesterday<br/></th>
<?php } ?>
                <th scope='col' style='<?php echo $thStyle?>'>Total*<br/></th>
            </tr>
        </thead>
        <tbody>
<?php 
    foreach($mostViewed as $title=>$countMap) {
        echo "<tr>";
        /* ROW TITLE */
        echo "<td style='<?php echo $tdStyle ?>' >";
        echo $title;
        echo "</td>";
        /* DAILY */
        echo "<td style='<?php echo $tdStyle ?>'>";
        if (array_key_exists('daily', $countMap))
            echo $countMap['daily'];
        echo "</td>";
        /* TOTAL */
        echo "<td style='<?php echo $tdStyle ?>'>";
        if (array_key_exists('total', $countMap))
            echo $countMap['total'];

        echo "</td>";

        echo "</tr>"; 
    }
        echo '</tbody>';
        echo "</table>";
?>


<?php /****** Most Contacted Rentals Today ******/ ?>
<table id="hor-minimalist-b" style='font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;font-size: 12px;background: #fff;margin: 15px;width: 480px;border-collapse: collapse;text-align: left;' summary="Your Most Viewed Rentals">
        <thead>
            <tr>
                <th scope='col' style='<?php echo $thStyle?>'>Your Most Contacted Rentals Still Available</th>
<?php
if ($timePeriod === 'Weekly'){ ?>
            <th scope='col' style='<?php echo $thStyle?>'>Last Week<br/></th>
<?php } else { ?>
            <th scope='col' style='<?php echo $thStyle?>'>Yesterday<br/></th>
<?php } ?>
                <th scope='col' style='<?php echo $thStyle?>'>Total*<br/></th>
            </tr>
        </thead>
        <tbody>
<?php 
    foreach($mostContacted as $title=>$countMap) {
        echo "<tr>";
        /* ROW TITLE */
        echo "<td style='<?php echo $tdStyle ?>' >";
        echo $title;
        echo "</td>";
        /* DAILY */
        echo "<td style='<?php echo $tdStyle ?>'>";
        echo $countMap['daily'];
        echo "</td>";
        /* TOTAL */
        echo "<td style='<?php echo $tdStyle ?>'>";
        echo $countMap['total'];
        echo "</td>";

        echo "</tr>"; 
    }
        echo '</tbody>';
        echo "</table>";
?>



<?php /****** Least Viewed Rentals ******/ ?>
<table id="hor-minimalist-b" style='font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;font-size: 12px;background: #fff;margin: 15px;width: 550px;border-collapse: collapse;text-align: left;' summary="Your Least Viewed Rentals">
        <thead>
            <tr>
                <th scope='col' style='<?php echo $thStyle?>'>Your Least Viewed Rentals <br/>Still Available - View Count:</th>
<?php
if ($timePeriod === 'Weekly'){ ?>
            <th scope='col' style='<?php echo $thStyle?>'>Last Week<br/></th>
<?php } else { ?>
            <th scope='col' style='<?php echo $thStyle?>'>Yesterday<br/></th>
<?php } ?>
                <th scope='col' style='<?php echo $thStyle?>'>Total*<br/></th>
            </tr>
        </thead>
        <tbody>
<?php 
    foreach($leastViewed as $title=>$countMap) {
        echo "<tr>";
        /* ROW TITLE */
        echo "<td style='<?php echo $tdStyle ?>' >";
        echo $title;
        echo "</td>";
        /* DAILY */
        echo "<td style='<?php echo $tdStyle ?>'>";
        if (array_key_exists('daily', $countMap))
            echo $countMap['daily'];
        echo "</td>";
        /* TOTAL */
        echo "<td style='<?php echo $tdStyle ?>'>";
        if (array_key_exists('total', $countMap))
            echo $countMap['total'];

        echo "</td>";

        echo "</tr>"; 
    }
        echo '</tbody>';
        echo "</table>";
?>

If you've leased out all of your properties or if you'd like to stop receiving this email, reply to this email and let us know.
<br/><br/>
*Since <?php echo $sinceDate; ?>

</body>
</div>