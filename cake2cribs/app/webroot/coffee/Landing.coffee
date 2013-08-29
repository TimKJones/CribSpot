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

		$(".typeahead").val("University of Michigan-Ann Arbor")

	@Submit: () ->
		location = $("#search-text").val()
		if location not in @schoolList 
			A2Cribs.UIManager.Error location + " is not a valid location."
			return false

		window.location = $('#sublet-redirect').attr('href') + "/" + location.split(' ').join('_');


