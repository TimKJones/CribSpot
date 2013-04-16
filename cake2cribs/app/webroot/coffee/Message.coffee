
class A2Cribs.Messages
	

	@setupUI:()->
		# Add a handler to the send reply button
		$('#send_reply').click =>
			@sendReply()
		$('#view_unread_cb').change =>
			@toggleUnreadConversations()

		$('#refresh_content').click =>
			@refresh()

		$('#current_conversation').scroll (event) =>
			@MessageScrollingHandler(event)

		$('#meaning').click =>
			$('#hidden-meaning').fadeToggle()

		$('#delete_conversation').click =>
			@DeleteConversation()

		# Refresh (Load) all the ajax content
		@refresh()


		

	#Alligns the bottom of the message list item provided to the bottom of the message window
	@ScrollMessagesTo:(mli)->
		cc = $('#current_conversation')
		dist = (cc.offset().top + cc.innerHeight())-(mli.offset().top+mli.innerHeight() + 10) # 10px for padding
		cc.scrollTop(cc.scrollTop()-dist)


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

		if @CurrentConversation != -1
			@refreshParticipantInfo()
			@refreshMessages()

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

			@attachConversationListItemHandler() 

	@toggleUnreadConversations:()->
		@ViewOnlyUnread = $('#view_unread_cb').is ':checked' 
		@refreshConversations()

	# Updates the cache if needbe and updates the visible participant info for the current 
	# Conversation
	@refreshParticipantInfo:()=>
		participantid = @CurrentParticipantID
		conversation_id = @CurrentConversation
		# Is the participant's info already in the cache?
		if @ParticipantInfoCache[participantid]?
			@setParticipantInfoUI @ParticipantInfoCache[participantid]
			return

		# Not in cache so fetch save and display
		url = url = myBaseUrl + "messages/getParticipantInfo/" + conversation_id +  "/" 
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
		$(".participant-university").html participant['University']['name']
		
		A2Cribs.VerifyManager.getVerificationFor(participant).then (verification_info)->
			veripanel = $('#verification-panel')

			if verification_info.verified_email
				veripanel.find('#veri-email  i:last-child').removeClass('unverified icon-remove-sign').addClass('verified icon-ok-sign')

			if verification_info.verified_edu
				veripanel.find('#veri-edu  i:last-child').removeClass('unverified icon-remove-sign').addClass('verified icon-ok-sign')
			else
				$('.participant-university').html("No Associated University")

			if verification_info.verified_fb
				url = "https://graph.facebook.com/#{verification_info.fb_id}/picture?width=480"
				console.log(url)
				$('#p_pic').attr 'src', url
				veripanel.find('#veri-fb  i:last-child').removeClass('unverified icon-remove-sign').addClass('verified icon-ok-sign')
				if verification_info.mut_friends? and not isNaN(verification_info.mut_friends)
					veripanel.find('#participant-friends').html("- #{verification_info.mut_friends} mutual")
				else if verification_info.tot_friends? and not isNaN(verification_info.tot_friends)
					veripanel.find('#participant-friends').html("- #{verification_info.tot_friends} friends")

			if verification_info.verified_tw
				veripanel.find('#veri-tw  i:last-child').removeClass('unverified icon-remove-sign').addClass('verified icon-ok-sign')  
				if verification_info.tot_followers? and not isNaN(verification_info.tot_followers)
					veripanel.find("#participant-followers").html("- #{verification_info.tot_followers} followers")


	@loadConversation:(event)->

		$('#cli_' + @CurrentConversation).removeClass 'selected_conversation'
		$('#cli_' + @CurrentConversation).addClass 'read_conversation'	

		$(event.currentTarget)
			.addClass('selected_conversation')
			.removeClass('unread_conversation')

		@CurrentConversation = parseInt $(event.currentTarget).attr('convid')
		@CurrentParticipantID = $('#cli_' + @CurrentConversation).find('meta')
												.attr('participantid')

		$('#message_reply').show()
		$('#participant_info_short').show()

		# Get the title of the conversation being selected and make the title of ther
		# center messaging window have that title as well.
		title = $('#cli_' + @CurrentConversation).find('.conversation_title').text()
		sublet_url = $('#cli_' + @CurrentConversation + ' a').attr 'href'
		$('#listing_title').text(title).attr('href', sublet_url)

		@refreshParticipantInfo()
		@refreshUnreadCount()
		@refreshMessages()

	@loadMessages:(page, align_bottom=false)->
		url = myBaseUrl + "messages/getMessages/" + @CurrentConversation +  "/" + page + "/" 
		
		# We want to put a hold on loading
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

			@attachConversationListItemHandler()
			
		.fail =>
			@NumMessagePages = 0;
		

	@attachConversationListItemHandler:()->
		# We use a one time event so the user can't stack a backlog 
		# of loadConversation Events
		# After a conversation is loaded events will be put in again.
		$('.conversation_list_item').one 'click', (event)=>
				@loadConversation(event)

	@refreshMessages:(event)->
		@NumMessagePages = 1
		message_list = $('#message_list')
		message_list.html ''
		@loadMessages(@NumMessagePages, true)
	
	@sendReply:(event)->
		# Gather the data to send to the server
		message_text = $('#message_text textarea').val()	
		if message_text.length == 0
			alertify.error("Message can not be empty", 1500)
			return false
		# Disable the submit button
		$('#send_reply').attr 'disabled','disabled'
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
			response = JSON.parse(data);
			if data?.success == false
				alertify.error("Something went wrong while sending a reply, please refresh the page and try again", 2000);
		.always ()=>
			$('#send_reply').removeAttr 'disabled'
		false

	@DeleteConversation:()->
		url = myBaseUrl + "messages/deleteConversation/"
		request_data = {
			'conv_id': @CurrentConversation
		}

		$.post url, request_data, (response)=>			
			try
				data = JSON.parse response
			catch e
				alertify.error 'Failed to delete the conversation', 1500
				return

			if data.success == 1
				alertify.success 'Conversation deleted', 1500
				@CurrentConversation = -1
				@CurrentParticipantID = -1
				A2Cribs.Dashboard.HideContent('messages')
				@refresh()
			else
				alertify.error 'Failed to delete the conversation', 1500

	@Direct: (directive)->
		
		if directive.data?
			conv_id = parseInt(directive.data.conversation_id)
			@CurrentConversation = conv_id
			participant_id = parseInt(directive.data.participant_id)
			@CurrentParticipantID = participant_id
			$('#listing_title').text directive.data.title

	@init:(user)->
		@me = user
		@ViewOnlyUnread = false
		if not @CurrentConversation?
			@CurrentConversation = -1
		@DropDownVisible = false
		@NumMessagePages = -1
		if not @CurrentParticipantID?
			@CurrentParticipantID = -1
		@ParticipantInfoCache = {}
		# A flag to prevent users from continuously reloading
		# the same conversation.
		@LoadingMessages = false;



