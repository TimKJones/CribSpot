<?php echo $this->Html->css('/less/Tours/calendar_picker.less?','stylesheet/less', array('inline' => false)); ?>
<?php
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	echo $this->Html->script('src/Tour.js', array('inline' => false));
}
?>

<div id="calendar_picker">
	<h2>What are all the times you are able to visit?</h2>
	<div class="date_selecter">
		<div id="prev_date" class="date_changer disabled">
			<i class="icon-chevron-sign-left"></i>
		</div>
		<div class="date_range">Oct 22-Oct 23</div>
		<div id="next_date" class="date_changer">
			<i class="icon-chevron-sign-right"></i>
		</div>
	</div>
	<!-- Table  -->
	<table>
		<tr id="calendar_table_dates">
			<td></td>
			<th>Wednesday, October 23</th>
			<th>Wednesday, October 23</th>
			<th>Wednesday, October 23</th>
		</tr>
		<?php

		$time_slots = [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];

		$slot_number = sizeof($time_slots);

		for ($time_slot_index=0; $time_slot_index < $slot_number - 1; $time_slot_index++) {
			$start_string = ($time_slots[$time_slot_index] > 12) ? $time_slots[$time_slot_index] - 12 : $time_slots[$time_slot_index];
			$start_string .= ($time_slot_index < 3) ? "am" : "pm";
			$end_string = ($time_slots[$time_slot_index + 1] > 12) ? $time_slots[$time_slot_index + 1] - 12 : $time_slots[$time_slot_index + 1];
			$end_string .= ($time_slot_index + 1 < 3) ? "am" : "pm";

			echo "<tr><td>" . $start_string . "</td>";
			for ($i=0; $i < 3; $i++) {
				echo "<td id='ts_" . $i . $time_slots[$time_slot_index] . "' class='time_slot' data-dateoffset='" . $i . "' data-timeslot='" . $time_slots[$time_slot_index] . "'>";
				echo "<div class='time_slot_filler hide'><i class='icon-plus-sign'></i>";
				echo strtoupper($start_string) . " - " . strtoupper($end_string) . "</div></td>";
			}
			echo "</tr>";
		}

		?>
	</table>
	<div class="btn-row">
		<button id="request_times_btn" class="button">Request My Times</button>
	</div>
</div>