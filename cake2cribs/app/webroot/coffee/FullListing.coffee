class A2Cribs.FullListing

	@SetupUI: ->
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
			@div.find("#contact_owner").hide()
			@div.find("#contact_message").slideDown()

		@div.find("#message_cancel").click () =>
			@div.find("#contact_message").slideUp 'fast', () =>
				@div.find("#contact_owner").show()

		@div.find("#message_send").click () =>
			$("#message_send").button("loading")
			$.ajax
				url: myBaseUrl + "Messages/messageSublet"
				type: "POST"
				data:
					listing_id: 1
					message_body: $("#message_area").val()

				success: (response) =>
					data = JSON.parse response
					if data.success
						$("#message_area").val ""
						A2Cribs.UIManager.Success "Message Sent!"
					else
						A2Cribs.UIManager.Error "Message Failed! Please Try Again."
					$("#message_send").button "reset"

	@Directive: (directive) ->
		if directive.contact_owner?
			@div.find("#contact_owner").click()