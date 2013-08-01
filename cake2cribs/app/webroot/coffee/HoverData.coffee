class A2Cribs.HoverData extends A2Cribs.Object
	constructor: (hoverData) ->
		super "hoverData", hoverData

	###
	Overwrite Object.GetId
	Want to return the marker_id to which this hoverData belongs
	###
	GetId: () ->
		if this[0]? and this[0].Listing? and this[0].Listing.marker_id?
			return parseInt this[0].Listing.marker_id

		return null