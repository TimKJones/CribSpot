<b>Student Information</b><br>
<?php
echo "Name: " . $student_data['first_name']." ".$student_data['last_name']. '<br>';
echo "Email: " . $student_data['email'].'<br>';
echo "Phone: " . $student_data['phone'].'<br>';
echo "University: " . $student_data['registered_university'].'<br>';
echo "Year: " . $student_data['student_year'].'<br>';
echo "Listing URL: " . $listing_url.'<br>';
echo "<br>";

echo "<b>Times Requested</b><br>";
foreach ($tour_data as $tour){
	echo "Time: " . $tour['time'] . '<br>';
	echo "Click to Confirm: " . $tour['confirm_link'] . '<br>';
	echo "<br>";
}

echo "<br>";

echo "<b>Special Notes from Student</b><br>";
echo $notes . '<br>';
echo '<br>';

echo "<b>Property Manager Information</b><br>";
echo "ID: " . $pm_data['id'] . '<br>';
echo "Company: " . $pm_data['company_name'] . '<br>';
echo "Email: " . $pm_data['email'] . '<br>';
echo "Phone: " . $pm_data['phone'] . '<br>';
?>