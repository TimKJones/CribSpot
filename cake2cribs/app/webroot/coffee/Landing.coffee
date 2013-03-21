class A2Cribs.Landing
	@Init: (locations) ->
		@schoolList = Array()
		for location in locations
			@schoolList.push location.University.name

		that = this
		$(() ->
			$( ".typeahead" ).typeahead({
				source: that.schoolList
			});
		)

	@Submit: () ->
		location = $("#search-text").val()
		if location not in @schoolList 
			alert location + " is not a valid location."
			return false

		window.location = "/map/sublet/" + location.split(' ').join('_');


