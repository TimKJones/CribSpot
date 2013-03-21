class A2Cribs.SubletAdd

	@setupUI:() ->
		$('#goToStep2').click (e) =>
			e.preventDefault()
			@subletAddStep1() 



			
	@subletAddStep1:() ->
		url = "sublets/ajax_add_create"
		request_data = {
			Sublet: {
				university: $('#universitiesInput').val()
				building_type_id: $('#SubletBuildingTypeId').val()
				name: $('#SubletName').val()
			}
			
		}
		$.post url, request_data, (response) =>
			console.log request_data
			console.log response
			data = JSON.parse response
			console.log data
			if data.registerStatus == 1
				window.location.href= '/dashboard'
			else
				$('#registerStatus').empty()
			


