class A2Cribs.ShareManager

	###
	Prompts user to share listing on facebook using facebook api.
	INPUT:
		university_encoded = University with spaces replaced by '_' (as it is in url)
		address_encoded = address for listing encoded in the same fasion
		listing_id = listing to be shared
	###
	@GetShareUrl: (university_encoded=null, address_encoded=null, sublet_id=null) ->
		if university_encoded == null or address_encoded == null or sublet_id == null
			return

		url = 'http://localhost/map/sublet/' + university_encoded + '/' + address_encoded + '/' + sublet_id
		return url

	@ShareListingOnFacebook: (university_encoded=null, address_encoded=null, sublet_id=null) ->
		url = A2Cribs.ShareManager.GetShareUrl(university_encoded, address_encoded, sublet_id)

		###sublet = null
		if A2Cribs.Map.IdToListingMap[sublet_id] != undefined
			sublet = A2Cribs.Map.IdToListingMap[sublet_id]
		else
			A2Cribs.Map.GetSubletData sublet_id###

		fbObj = 
			method: 'feed'
			link: url
			name: 'Cribspot'
			caption: 'Share this listing with your friends!'
			description: 'description'
		FB.ui fbObj, A2Cribs.ShareManager.ShareListingOnFacebook

	@InitTweetButton: (university_encoded=null, address_encoded=null, sublet_id=null) ->
		if university_encoded == null or address_encoded == null or sublet_id == null
			return

		url = A2Cribs.ShareManager.GetShareUrl(university_encoded, address_encoded, sublet_id)
		$('#twitterDiv iframe').remove();
		tweetBtn = $('<a></a>').addClass('twitter-share-button')
		.attr('href', 'http://twitter.com/share')
		.attr('data-url', url)
		.attr('data-text','Check out my sublease on Cribspot.com! ' + url)
		.attr('data-via', 'TheCribspot')
		$('#twitterDiv').append(tweetBtn);

		twttr.widgets.load();

	@GetSubletData: (sublet_id) ->
		$.ajax
			url: myBaseUrl + "Sublets/GetSubletData/" + sublet_id
			type: "GET"
			success: A2Cribs.Map.GetSubletDataCallback