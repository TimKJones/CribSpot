class A2Cribs.PageHeader
	# Gets the users current unread conversations count and 
	# displays that in the upper right hand covern of the icon provided

	@renderUnreadConversationsCount: ()->
		url = myBaseUrl + "messages/getUnreadCount"
		$.get url, (data)=>
			try
				response_data = JSON.parse data
			catch error
				return
				# console.log "user is not logged in so error occured while checking messages" + error 

			count = response_data.unread_conversations
			notification = $('#unread-conversation-notification')
			if count == 0
				notification.hide()
			else
				notification.html response_data.unread_conversations
				notification.show()