class A2Cribs.UIManager

	@Alert: (message) ->
		alertify.alert message

	@Error: (message) ->
		alertify.error message

	@CloseLogs: () ->
		$('.alertify-log').remove()