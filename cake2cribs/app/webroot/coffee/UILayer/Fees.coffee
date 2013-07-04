class A2Cribs.UILayer.Fees

	###
	Return an array of Fee objects 
	###
	@GetFees: () ->
		fees = []

		#Administration
		fees.push fee_id: null, description: "Admin", amount: null
		#Parking
		fees.push fee_id: null, description: "Parking", amount: 25
		#Furniture
		fees.push fee_id: null, description: "Furniture", amount: null
		#Pets
		fees.push fee_id: null, description: "Pets", amount: 50
		#Upper Floor
		fees.push fee_id: null, description: "Upper Floor", amount: null
		#Cleaning
		fees.push fee_id: null, description: "Cleaning", amount: 50

		return fees