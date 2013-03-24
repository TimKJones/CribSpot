class A2Cribs.SubletAdd

	@setupUI:() ->

		$('#goToStep2').click (e) =>
			e.preventDefault()
			#$('#server-notice').dialog2("options", {content:"Sublets/ajax_add2"});

			@subletAddStep1() 



			
	@subletAddStep1:() ->
		url = "sublets/ajax_add_create"
		request_data = {
			Sublet: {
				university_id: parseInt(window.universitiesArray.indexOf($('#universitiesInput').val()) + parseInt(window.universitiesMap[0].University.id))
				university: $('#universitiesInput').val()
				unit_number: $('#SubletUnitNumber').val()
				address: $("#addressToMark").val()
				building_type_id: $('#SubletBuildingTypeId').val()
				name: $('#SubletName').val()
				latitude: $('#updatedLat').text()
				longitude: $('#updatedLong').text()
			}
			CurrentStep: 1
			#console.log(universitiesArray.indexOf(request_data.Sublet.university));
			
		}

		$.post url, request_data, (response) =>
			console.log(response)
			data = JSON.parse response
			console.log data
			$('#server-notice').dialog2("options", {content:"Sublets/ajax_add2"});
			#window.location.href= '/dashboard'
			#if data.registerStatus == 1
			#	window.location.href= '/dashboard'
			#else
			#	$('#registerStatus').empty()
			


