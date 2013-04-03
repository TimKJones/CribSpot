<div id="report-bug" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Report a Bug</h3>
	</div>
	<div class="modal-body container-fluid">
		<div class="row-fluid">
			<label for="email" class="span3">Email:</label>
			<input id = 'email-input' type="text" name = 'email' class="span9" placeholder='We want to let you know when we fix the bug'>
		</div>
		<div class="row-fluid">
			<label for="description" class="span3">Description:</label>
			<textarea id = 'description-input' name = "description" class="span9" placeholder="What went wrong?"></textarea>
		</div>
		<div class="row-fluid">
			<label for="additional_info" class="span3">Additional Info:</label>
			<textarea id = 'add-info-input' name = 'additional_info' class="span9" placeholder="Anything else we should know?"></textarea>
		</div>
		<button class = 'btn' id='send-report-btn'>Send</button>

	</div>
</div>

<script>
	var sendBugReport = function(){
		var container = $('#report-bug');
		var email = container.find('#email-input').val();
		var description = container.find('#description-input').val()
		var add_info = container.find('#add-info-input').val();

		data = {
			'email': email,
			'description':description,
			'add_info':add_info
		};
		console.log(data);

		$.ajax({
			type: "POST",
			url: myBaseUrl + 'utility/sendBugReport',
			data: data,
			success: function(response){
				alertify.success('Bug report sent. Thank You!', 1500);
				container.find('button.close').trigger('click');
				container.find('input').val('');
				container.find('textarea').val('');
			},
			error: function(response){
				alertify.error('Bug report failed to send, please try again.', 1500);
				
			},
		});
	};
	$("#report-bug").ready(function() {
    	$('#send-report-btn').click(sendBugReport);
	});

</script>