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
			$('#save_btn').click =>
				@SaveAccount()

	@Direct: (directive)->

	@SaveAccount:()->
		$('#save_btn').attr 'disabled','disabled'
		first_name = $('#first_name_input').val()
		last_name = $('#last_name_input').val()
		data = {
			'first_name': first_name,
			'last_name': last_name,
		}

		$.post myBaseUrl + 'users/ajaxEditUser', data, (response)->
			# console.log response
			json_response = JSON.parse(response)
			if json_response.success == 1
				Alertify.log.success('Account Saved')
				console.log JSON.parse(json_response.user)
			else
				Alertify.log.error('Account Failed to Save: ' + json_response.message)


			$('#save_btn').removeAttr 'disabled'





