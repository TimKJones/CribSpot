class A2Cribs.UILayer.Fees

	###
	Return an array of Fee objects 
	###
	@GetFees: () ->
		fees = []

		#Administration
		fees.push fee_id: 160, description: "Admin", amount: 69
		#Parking
		fees.push fee_id: 161, description: "Parking", amount: 25
		#Furniture
		fees.push fee_id: 162, description: "Furniture", amount: null
		#Pets
		fees.push fee_id: 163, description: "Pets", amount: 50
		#Upper Floor
		fees.push fee_id: 164, description: "Upper Floor", amount: null
		#Cleaning
		fees.push fee_id: 165, description: "Cleaning", amount: 50

		return fees