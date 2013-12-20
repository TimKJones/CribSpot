class A2Cribs.UIManager
	@_num_loaders = 0

	###
	Show Loader
	Takes a div (otherwise null). Shows loader
	in the middle of the div otherwise the 
	middle of the screen. Keeps track of the 
	amount of loaders being displayed
	TODO: ADD DIV SUPPORT (JUST GLOBAL FOR NOW)
	###
	@ShowLoader: (div) ->
		@_num_loaders++ 
		$("#loader").show()

	###
	Hide Loader
	Hides the spinner based on the div. If no 
	div given then main loader. Only removes
	the loader if loader count is 0.
	TODO: ADD DIV SUPPORT (JUST GLOBAL FOR NOW)
	###
	@HideLoader: (div) ->
		if --@_num_loaders is 0
			$("#loader").hide()

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