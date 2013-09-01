class A2Cribs.NewspaperTest

	@SendPost: () ->
		$.ajax
			url: "http://ec2-54-214-177-171.us-west-2.compute.amazonaws.com/FeaturedListings/newspaper?secret_token="+encodeURIComponent("Yx4+aP%gs2dh2uG?1VyKQV2OBP-3eKBI")
			type:"GET"
			context: this
			success: (response) ->
				console.log JSON.parse response
			failure: (response) ->
				console.log response
