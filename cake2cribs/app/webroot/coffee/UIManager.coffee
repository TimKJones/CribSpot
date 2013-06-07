class A2Cribs.UIManager

	@Alert: (message) ->
		alertify.alert message

	@Error: (message) ->
		alertify.error message

	@Success: (message) ->
		alertify.success message

	@CloseLogs: () ->
		$('.alertify-log').remove()