class A2Cribs.FullListing

	@SetupUI: (@listing_id) ->
		@div = $(".full_page")

		@div.find(".show_scheduling").click (event) =>
			if A2Cribs.Login?.logged_in is yes
				if not $(event.currentTarget).attr("href")?
					$("#scheduling_tour_tab").click()
				else
					$(event.currentTarget).tab('show')
					$(document).trigger "track_event", ["Full Page", "Schedule Tour Clicked", "", listing_id]
			else
				$("#signup_modal").modal("show").find(".signup_message").text "Please sign in to schedule a tour."
				$(document).trigger "track_event", ["Login", "Login required", "Schedule Tour", listing_id]
			event.preventDefault()

		@div.find(".image_preview").click (event) =>
			image = $(event.delegateTarget).css "background-image"
			@div.find(".image_preview.active").removeClass "active"
			$(event.delegateTarget).addClass "active"
			@div.find("#main_photo").css "background-image", image

		@div.find(".page_right").click (event) =>
			if @div.find(".image_preview.active").next().length
				next_photo = @div.find(".image_preview.active").next()
				@div.find(".image_preview.active").removeClass "active"
				next_photo.addClass "active"
				@div.find("#main_photo").css "background-image", next_photo.css "background-image"

		
		@div.find(".page_left").click (event) =>
			if @div.find(".image_preview.active").prev().length
				next_photo = @div.find(".image_preview.active").prev()
				@div.find(".image_preview.active").removeClass "active"
				next_photo.addClass "active"
				@div.find("#main_photo").css "background-image", next_photo.css "background-image"

		@div.find("#contact_owner").click () =>
			if A2Cribs.Login?.logged_in is yes
				$(document).trigger "track_event", ["Full Page", "Contact Owner Clicked", "", listing_id]
				@div.find("#contact_owner").hide()
				@div.find("#contact_message").slideDown()
			else
				$("#signup_modal").modal("show").find(".signup_message").text "Please sign in to contact the owner."
				$(document).trigger "track_event", ["Login", "Login required", "Contact Owner", listing_id]

		@div.find("#message_cancel").click () =>
			@div.find("#contact_message").slideUp 'fast', () =>
				@div.find("#contact_owner").show()

		@div.find("#message_send").click () =>
			$("#message_send").button("loading")
			$(document).trigger "track_event", ["Message", "Sending Message", "", listing_id]
			$("#loader").show()
			$.ajax
				url: myBaseUrl + "Messages/messageSublet"
				type: "POST"
				data:
					listing_id: @listing_id
					message_body: $("#message_area").val()

				success: (response) =>
					data = JSON.parse response
					if data.success
						$("#message_area").val ""
						A2Cribs.UIManager.Success "Message Sent!"
						$(document).trigger "track_event", ["Message", "Message Sent", "", listing_id]
					else
						if data.message?
							A2Cribs.UIManager.Error data.message
						else
							A2Cribs.UIManager.Error "Message Failed! Please Try Again."
						$(document).trigger "track_event", ["Message", "Message Failed", "", listing_id]
					$("#message_send").button "reset"

				complete: ->
					$("#loader").hide()

	@Directive: (directive) ->
		if directive.contact_owner?
			@div.find("#contact_owner").click()
		else if directive.schedule?
			@div.find("#scheduling_tour_tab").click()
