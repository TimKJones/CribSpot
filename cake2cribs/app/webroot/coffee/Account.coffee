class A2Cribs.Account

	@setupUI:()->
		# Get all the universities data
		url = myBaseUrl + "university/getAll/"
		$.get url, (data)=>
			@UniversityData = JSON.parse data
			@UniversityNames = []
			@UniversityID = []
			_.each @UniversityData, (value, key, list)=>
				@UniversityNames[key] = value['University']['name']
				@UniversityID[key] = value['University']['id']

			$('#university').typeahead {source: @UniversityNames}
