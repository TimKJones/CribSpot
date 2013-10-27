<b>Cribspot Tour Confirmation</b><br><br>
<b>Thanks <?php echo $student_data['first_name']; ?>,</b><br><br>
We've received your request to schedule a tour for <?php echo $building_name;?>.  We'll be in touch with you shortly after we've scheduled a time.  Here is a list of the times you've said you're available:<br><br>
<?php
foreach ($tour_data as $tour){
	echo $tour['time'] . '<br>';
}
?>
<br>

If you have any questions about your upcoming tour, let us know! <br><br>

Thanks,<br><br>

The Cribspot Team