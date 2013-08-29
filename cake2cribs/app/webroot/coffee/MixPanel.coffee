class A2Cribs.MixPanel

	array_min = (arr) ->
		if arr?
			return Math.min.apply null, arr

	array_max = (arr) ->
		if arr?
			return Math.min.apply null, arr

	month_min = (arr) ->

	month_max = (arr) ->

	###
	Takes a listing or a marker
	Uses mixpanel to track the Listing Click event
	Object can be listing or marker
	display_type = small popup, large popup, full page
	###
	@Click: (object, display_type) ->
		if object == undefined or object == null
			return
		is_featured = 0
		if object.class_name is "listing"
			listing = object
			is_featured = parseInt(listing.listing_id) in A2Cribs.FeaturedListings.FLListingIds
			marker = A2Cribs.UserCache.Get "marker", listing.marker_id
		else if object.class_name is "marker"
			marker = object
			listings = A2Cribs.UserCache.GetAllAssociatedObjects 'listing', 'marker', marker.marker_id
			for listing in listings
				if parseInt(listing.listing_id) in A2Cribs.FeaturedListings.FLListingIds
					is_featured = 1
					break
		else if object.class_name is "rental"
			listing = A2Cribs.UserCache.Get "listing", object.listing_id
			is_featured = parseInt(listing.listing_id) in A2Cribs.FeaturedListings.FLListingIds
		else
			return false

		mixpanel_object =
			'listing type': marker?.GetBuildingType()
			'display type': display_type
			'is featured': false # needs to be figured out
			'listing_id': listing?.GetId()
			'marker_id': marker?.GetId()
			'university_id': A2Cribs.Map?.CurentSchoolId
			'filter minimum beds' : array_min A2Cribs.RentalFilter.FilterData?.Beds
			'filter maximum beds' : array_max A2Cribs.RentalFilter.FilterData?.Beds
			'filter minimum rent' : A2Cribs.RentalFilter.FilterData?.Rent?.min
			'filter maximum rent' : A2Cribs.RentalFilter.FilterData?.Rent?.max
			'filter start year': A2Cribs.RentalFilter.FilterData?.Dates?.year
			'filter minimum lease length' : A2Cribs.RentalFilter.FilterData?.LeaseRange?.min
			'filter maximum lease length' : A2Cribs.RentalFilter.FilterData?.LeaseRange?.max
			'filter building_type min': array_min 
			'filter building_type': array_min 
			'filter pets allowed' : A2Cribs.RentalFilter.FilterData?.PetsAllowed
			'filter parking available' : A2Cribs.RentalFilter.FilterData?.ParkingAvailable
			'filter air conditioning' : A2Cribs.RentalFilter.FilterData?.Air
			'filter utilities included' : A2Cribs.RentalFilter.FilterData?.UtilitiesIncluded

		# Loop through all building_types, months, beds
		if A2Cribs.RentalFilter.FilterData?.Beds?
			for bed, i in A2Cribs.RentalFilter.FilterData.Beds
				mixpanel_object["filter bed #{i}"] = bed

		if A2Cribs.RentalFilter.FilterData?.Dates?.months?
			for month, i in A2Cribs.RentalFilter.FilterData.Dates.months
				mixpanel_object["filter month #{i}"] = month

		if A2Cribs.RentalFilter.FilterData?.UnitTypes?
			for unit_type, i in A2Cribs.RentalFilter.FilterData.UnitTypes
				mixpanel_object["filter unit_type #{i}"] = unit_type


		mixpanel.track 'Listing Click', mixpanel_object


	###
	Post listing is a wrapper that appends Post Listing to each mixpanel event
	Actions such as Started, Marker Selected, Find Address on Map, Marker save complete,
	Add Unit, Overview started, Features started, Description started, Images started, Saved
	###
	@PostListing: (action, data) ->
		mixpanel.track "Post Listing - #{action}". data




