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
    width: 480px;
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
            'PhoneCalls' => 'Est. Phone Calls from Cribspot:',
            'Messages' => 'Messages Sent to You:'
        );
        $thStyle = 'font-size: 14px;font-weight: normal;color: #039;padding: 10px 8px;border-bottom: 2px solid #6678b1;';
        $tdStyle = 'border-bottom: 1px solid #ccc;color: #669;padding: 6px 8px;';
/****** DAILY METRICS REPORT ******/
    if (array_key_exists('company_name', $user))
        echo "<h3 id='company_name' style='text-align: center;'>" . $user['company_name'] ."</h3>";
?>
<body style='line-height: 1.6em;'>
    <table id="hor-minimalist-b" style='font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;font-size: 12px;background: #fff;margin: 15px;width: 480px;border-collapse: collapse;text-align: left;' summary="Daily Metrics Report">
        <thead>
            <tr>
                <th scope='col' style='<?php echo $thStyle?>'>Daily Metrics Report</th>
                <th scope='col' style='<?php echo $thStyle?>'>Today</th>
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
        echo "</table><br/>";
echo '</body>';
?>
</div>