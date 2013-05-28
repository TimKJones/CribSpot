class A2Cribs.EditSublet extends A2Cribs.SubletSave
	constructor: () ->
		@div = $('#edit_sublet_window')
		@setupUI()

	setupUI: () ->
		@div.find(".step-button").click (event) =>
			@div.find(".step-button").removeClass "active"
			$(event.currentTarget).closest(".step-button").addClass "active"
			@GotoStep $(event.currentTarget).closest(".step-button").attr("step")

	Reset: () ->
		@div.find('.step').eq(0).show().siblings().hide()
		@div.find(".step-button").removeClass "active"
		@div.find('.step-button').eq(0).addClass "active"
		super @div

	Edit: (sublet_id) ->
		$.ajax
			url: myBaseUrl + "Sublets/getSubletDataById/" + sublet_id
			type: "GET"
			success: (subletData) =>
				@Close()
				subletData = JSON.parse subletData
				if subletData.redirect? then window.location = subletData.redirect
				@PopulateInputFields subletData
				@Open()

			error: ()=>
				A2Cribs.UIManager.Alert  "An error occured while loading your sublet data, please try again."

	Save: () ->
		if @Validate()
			super @GetSubletObject()

	GetSubletObject: () ->
		super @div

	Close: () ->
		@Reset()
		@div.parent().hide()

	Open: () ->
		@div.parent().show()

	PopulateInputFields: (subletData) ->
		for k,v of subletData
			for p,q of v
				console.log k + "_" + p
				input = @div.find("#" + k + "_" + p)
				if input?
					if "checkbox" is input.attr "type" 
						input.prop "checked", q
					else
						input.val q

	GotoStep: (step) ->
		#if @Validate step + 1
		@div.find('.step').eq(step).show().siblings().hide()

