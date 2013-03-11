class A2Cribs.Realtor
	constructor: (@RealtorId, @Company, @email) ->
		if (@Company == null)
			@LoadRealtor(@RealtorId)

	LoadRealtor : ->
		

