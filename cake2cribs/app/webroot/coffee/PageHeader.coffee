class A2Cribs.PageHeader
	# Gets the users current unread conversations count and 
	# displays that in the upper right hand covern of the icon provided

	@renderUnreadConversationsCount: ()->
		url = myBaseUrl + "messages/getUnreadCount"
		$.get url, (data)=>
			response_data = JSON.parse data
			count = response_data.unread_conversations
			notification = $('#unread-conversation-notification')
			if count == 0
				notification.hide()
			else
				notification.html response_data.unread_conversations
				notification.show()