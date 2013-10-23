class A2Cribs.FullListing

	@SetupUI: (@listing_id) ->
		@div = $(".full_page")

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
			A2Cribs.MixPanel.Event "Contact PM",
						"listing_id": @listing_id
			if A2Cribs.Login?.logged_in is yes
				@div.find("#contact_owner").hide()
				@div.find("#contact_message").slideDown()
			else
				$("#signup_modal").modal("show").find(".signup_message").text "Please sign in to contact the owner."
				A2Cribs.MixPanel.Event "login required",
						"listing_id": @listing_id

		@div.find("#message_cancel").click () =>
			@div.find("#contact_message").slideUp 'fast', () =>
				@div.find("#contact_owner").show()

		@div.find("#message_send").click () =>
			$("#message_send").button("loading")
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
						A2Cribs.MixPanel.Event "message sent",
							"listing_id": @listing_id
					else
						if data.message?
							A2Cribs.UIManager.Error data.message
						else
							A2Cribs.UIManager.Error "Message Failed! Please Try Again."
					$("#message_send").button "reset"

	@Directive: (directive) ->
		if directive.contact_owner?
			@div.find("#contact_owner").click()