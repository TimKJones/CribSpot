class A2Cribs.UIManager

	@Alert: (message) ->
		alertify.alert message

	@Error: (message) ->
		alertify.error message, 7000

	@Success: (message) ->
		alertify.success message

	@CloseLogs: () ->
		$('.alertify-log').remove()

	@FlashMessage: () ->
		if flash_message?
			@[flash_message.method] flash_message.message, flash_message.callback

	@Confirm: (message, callback) ->
		alertify.set
			buttonFocus: "cancel"
		alertify.confirm message, callback

	@ConfirmBox: (message, labels, callback) ->
		alertify.set
			labels: labels
			buttonFocus: "cancel"
		alertify.confirm message, callback

$(document).ready () =>
	setTimeout (() => A2Cribs.UIManager.FlashMessage()), 2000