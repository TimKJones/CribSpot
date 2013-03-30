class A2Cribs.SubletEdit

	@Init: (subletData) ->
		A2Cribs.Cache.SubletEditInProgress = new A2Cribs.SubletInProgress() 	

	@SaveStep1: () ->

	@SaveStep2: () ->

	@InitStep1: () ->
		if A2Cribs.Cache.SubletData == undefined
			return

		subletData = A2Cribs.Cache.SubletData
		$('#universityName').val(subletData.University.name)
		$('#SubletBuildingTypeId').val(subletData.BuildingType.name)
		$('#SubletName').val(subletData.Marker.alternate_name)
		$('#SubletUnitNumber').val(subletData.Sublet.unit_number)
		$("#addressToMark").val(subletData.Marker.street_address)
		$("#formattedAddress").val(subletData.Marker.street_address)
		$('#updatedLat').val(subletData.Marker.latitude)
		$('#updatedLong').val(subletData.Marker.longitude)
		$("#city").val(subletData.Marker.city)
		$("#state").val(subletData.Marker.state)
		$("#postal").val(subletData.Marker.zip)
		A2Cribs.CorrectMarker.FindSelectedUniversity()
		A2Cribs.CorrectMarker.FindAddress()

	@InitStep2: (subletData) ->


