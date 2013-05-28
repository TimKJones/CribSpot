class A2Cribs.PostSublet extends A2Cribs.SubletSave
	constructor: () ->
		@div = $('#post-sublet-modal')
		@currentStep = 0
		### INIT STEPS ###
		@setupUI()

	setupUI: () ->
		@ProgressBar =  new A2Cribs.PostSubletProgress $('.post-sublet-progress'), @currentStep
		@div.find("#address-step").siblings().hide();

		@div.find(".next-btn").click (event)=>
			if @Validate @currentStep + 1
				$(event.currentTarget).closest(".step").hide().next(".step").show()
				@currentStep++
				@ProgressBar.next()
		
		
		@div.find(".back-btn").click (event)=>
			$(event.currentTarget).closest(".step").hide().prev(".step").show()
			@currentStep--
			@ProgressBar.prev()

		super @div

	Reset: () ->
		# Reset Progress Bar
		@ProgressBar.reset()

		# Set Current Step to first
		@div.find('.step').eq(0).show()
		@div.find('.step').eq(0).siblings().hide()
		super @div

	Save: () ->
		if @Validate()
			super @GetSubletObject()

	Validate: (step_ = 3) ->
		super step_, @div


	GetSubletObject: ->
		super @div
