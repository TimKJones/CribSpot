class A2Cribs.Messages
	

	@setupUI:()->
		# Add a handler to the send reply button
		$('#send_reply').click =>
			@sendReply()
		$('#view_unread_cb').change =>
			@toggleUnreadConversations()

		$('#refresh_content').click =>
			@refresh()

		# $('#conversations_list_header').click =>
		# 	@toggleDropdown()

		$('#current_conversation').scroll (event) =>
			@MessageScrollingHandler(event)

		$('#meaning').click =>
			$('#hidden-meaning').fadeToggle()

		# Refresh (Load) all the ajax content
		@refresh()


		

	#Alligns the bottom of the message list item provided to the bottom of the message window
	@ScrollMessagesTo:(mli)->
		cc = $('#current_conversation')
		dist = (cc.offset().top + cc.innerHeight())-(mli.offset().top+mli.innerHeight() + 10) # 10px for padding
		cc.scrollTop(cc.scrollTop()-dist);



	@toggleDropdown:()->
		@DropDownVisible = !@DropDownVisible
		$('#conversation_drop_down').slideToggle 'fast'
		$('#toggle-conversations i').toggleClass 'icon-caret-right', !@DropDownVisible
		$('#toggle-conversations i').toggleClass 'icon-caret-down', @DropDownVisible
		$('#conversations_list_header').toggleClass 'shadowed', @DropDownVisible
		$('#conversations_list_header').toggleClass 'expanded', @DropDownVisible
		$('#conversations_list_header').toggleClass 'minimized', !@DropDownVisible
		$('.messages-content').toggleClass 'hidden', !@DropDownVisible

		

	@toggleMessageContent:()->




	@MessageScrollingHandler:(event)->
		#  Note: There is a race condition here where loadMessages will fail out a couple times
		#  Before @NumMessagePages gets set to 0. Like 4 events slip by the condition

		if $("#current_conversation").scrollTop() > 20 || @NumMessagePages == 0
			# Don't need to load new content yet
			return

		@NumMessagePages += 1
		@loadMessages(@NumMessagePages)




	@refresh:()->
		# refresh unread count information
		@refreshUnreadCount()
		# refresh the correct conversations list
		@refreshConversations()
		# refresh the currently selected conversation


		# BUG on auto loading conversation via direct link via email
		# The div elements for the conversation are loaded when we try and get the 
		# Participants information
		if @CurrentConversation != -1
			@refreshParticipantInfo()
			@refreshMessages()
			if !$("#conversation_drop_down").is ":visible"
				@toggleDropdown()
		# A2Cribs.Messages.set


	@refreshUnreadCount:()->
		url = myBaseUrl + "messages/getUnreadCount"
		$.get url, (data)=>
			response_data = JSON.parse data
			$('#unread_conversations_count span').html response_data.unread_conversations 

	@refreshConversations:()->
		url = myBaseUrl + "messages/getConversations"
		if @ViewOnlyUnread
			url+='?only_unread=1'

		$.get url, (data) =>
			$('.conversation_list').html data
			$('#conversation_count').html $('.conversation_list_item').length
			# add a handler to each of the conversation list items
			# so when the user clicks then the conversation is loaded
			
			# Update the classes for the selected conversation
			SelectedConversationDiv = $('#cli_' + @CurrentConversation)
			SelectedConversationDiv.addClass 'selected_conversation'
			SelectedConversationDiv.removeClass 'unread_conversation' #Just in case it was unread

			$('.conversation_list_item').each (index, element)=>
				$(element).click (event)=>
					@loadConversation(event)

	@toggleUnreadConversations:()->
		@ViewOnlyUnread = $('#view_unread_cb').is ':checked' 
		@refreshConversations()

	# Updates the cache if needbe and updates the visible participant info for the current 
	# Conversation
	@refreshParticipantInfo:()->
		participantid = $('#cli_' + @CurrentConversation).find('meta').attr('participantid')
		# Is the participant's info already in the cache?
		if @ParticipantInfoCache[participantid]?
			@setParticipantInfoUI @ParticipantInfoCache[participantid]
			return

		# Not in cache so fetch save and display
		url = url = myBaseUrl + "messages/getParticipantInfo/" + @CurrentConversation +  "/" 
		$.get url, (data)=>
			user_data = JSON.parse data
			@ParticipantInfoCache[user_data['id']] = user_data
			@setParticipantInfoUI @ParticipantInfoCache[participantid]

	# Sets all the UI elements that pertain to the current conversation's participant
	# Using the data provided in the participant object
	@setParticipantInfoUI:(participant)->
		$(".from_participant")
			.html(participant['first_name'])
			.attr('href', (myBaseUrl + 'users/view/' + participant['id']))
		$("#participant_university").html participant['University']['name']

	@loadConversation:(event)->
		$('#cli_' + @CurrentConversation).removeClass 'selected_conversation'
		$('#cli_' + @CurrentConversation).addClass 'read_conversation'		
		@CurrentConversation = parseInt $(event.currentTarget)
			.addClass('selected_conversation')
			.removeClass('unread_conversation')
			.attr('convid');
		$('#message_reply').show()
		$('#participant_info_short').show()

		# Get the title of the conversation being selected and make the title of ther
		# center messaging window have that title as well.
		title = $('#cli_' + @CurrentConversation).find('.conversation_title').text()
		$('#listing_title').text title

		# @CurrentConversation = parseInt SelectedConversationDiv.attr 'convid'
		@refreshParticipantInfo()
		@refreshUnreadCount()
		@refreshMessages()

	@loadMessages:(page, align_bottom=false)->
		url = myBaseUrl + "messages/getMessages/" + @CurrentConversation +  "/" + page + "/" 
		$.get url, (data, textStatus) =>
			message_list = $('#message_list')
			initial_height = message_list.innerHeight()

			$(data).hide().prependTo('#message_list').fadeIn()

			$('.mli').each (index, element)->
				new_height = $(@).find('.message_buble').height()
				$(@).css 'height', new_height + 'px'

			# $('#message_list').prepend data
			if align_bottom
				@ScrollMessagesTo($("#mli_0"));
			else
				# We need to fix the scroll so the content appears to be unchanged 
				# and the new content is silently loaded in the background

				diff = message_list.innerHeight() - initial_height
				$('#current_conversation').scrollTop($('#current_conversation').scrollTop() + diff)

			# See if there is more room to load content
			$('#current_conversation').trigger 'scroll'

		.fail =>
			@NumMessagePages = 0;

	@refreshMessages:(event)->
		@NumMessagePages = 1
		$('#message_list').html ''
		@loadMessages(@NumMessagePages, true)
	
	@sendReply:(event)->
		# Disable the submit button
		$('#send_reply').attr 'disabled','disabled'
		# Gather the data to send to the server
		message_text = $('#message_text textarea').val()	
		# Build the data object that we'll send to the server
		message_data = 
			'message_text': message_text
			'conversation_id': @CurrentConversation
		
		url = myBaseUrl + "messages/newMessage/"

		$.post url, message_data, (data)=>			
			@refreshMessages()
			@refreshConversations()			
			$('#message_text textarea').val('') # Clear the reply text field
			$('#send_reply').removeAttr 'disabled'
		false

	@init:(current_conversation = -1)->
		@ViewOnlyUnread = false
		@CurrentConversation = current_conversation
		@DropDownVisible = false
		@NumMessagePages = -1
		@ParticipantInfoCache = {}