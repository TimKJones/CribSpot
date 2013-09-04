class A2Cribs.NewspaperTest

	@SendPost: () ->
		$.ajax
			url: "http://www.cribspot.com/FeaturedListings/newspaper?secret_token="+encodeURIComponent("Yx4aPrgs2dhj7tx1VyKQV2OBP53eTFH")
			type:"GET"
			context: this
			success: (response) ->
				console.log JSON.parse response
			failure: (response) ->
				console.log response
