<?php 
	$partic_link = "$host_name/users/view/$participant[id]/";
	$view_msg = "$host_name/messages/?view_conversation=$conv_id";
	// BUG the links don't evaluate to clickable links, maybe its because the link I'm providing is from localhost
?>

<html>
	<h3>You've got a new message!</h3>
	<a href = '<?php echo $partic_link; ?>' ><?php echo $participant['first_name'];?> </a>
	just sent you a new message.

	<a href = '<?php echo $view_msg; ?>' >Click here to view the message</a>
</html>