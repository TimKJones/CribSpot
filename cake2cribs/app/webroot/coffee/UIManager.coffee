class A2Cribs.UIManager

	@Alert: (message) ->
		alertify.alert message

	@Error: (message) ->
		alertify.error message

	@Success: (message) ->
		alertify.success message

	@CloseLogs: () ->
		$('.alertify-log').remove()

	@FlashMessage: () ->
		if flash_message?
			@[flash_message.method] flash_message.message

	@Confirm: (message, callback) ->
		alertify.confirm message, callback

$(document).ready () =>
	setTimeout (() => A2Cribs.UIManager.FlashMessage()), 2000