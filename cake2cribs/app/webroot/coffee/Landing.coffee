class A2Cribs.Landing
	@Init: (locations) ->
		@schoolList = Array()
		@cityList = Array()
		for location in locations
			@schoolList.push location.School.school_name
			@cityList.push location.School.city

		that = this
		$(() ->
			$( ".typeahead" ).typeahead({
				source: that.schoolList.concat(that.cityList)
			});
		)

	@Submit: () ->
		location = $("#search-text").val()
		index = location in @cityList
		if location in @cityList
			location = @schoolList[@cityList.indexOf(location)];
		else if location not in @schoolList 
			alert location + " is not a valid location."
			return

		window.location = "/map/sublet/" + location.split(' ').join('_');

