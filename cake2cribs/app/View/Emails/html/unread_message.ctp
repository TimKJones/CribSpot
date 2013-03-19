<?php 
	// [NOTE] url is hard coded instead of using a php function like $_Server['Http_Host']
	// There was issue invalid links while testing on the dev enviorment

	$partic_link = "http://www.cribspot.com/users/view/$participant[id]/";
	$view_msg = "http://www.cribspot.com/messages/view/$conv_id";
	// BUG the links don't evaluate to clickable links, maybe its because the link I'm providing is from localhost
?>

<html>
	<h3>You've got a new message!</h3>
	<a href = '<?php echo $partic_link; ?>' ><?php echo $participant['first_name'];?></a>
	just sent you a new message.

	<a href = '<?php echo $view_msg; ?>' >Click here to view the message</a>
</html>