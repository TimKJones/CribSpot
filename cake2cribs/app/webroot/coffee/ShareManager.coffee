class A2Cribs.ShareManager

	###
	Creates a listing url from its individual components
	###
	@GetShareUrl: (listing_id, street_address, city, state, zip) ->
		if listing_id == null or street_address == null or city == null or state == null or zip == null
			return null
		street_address = street_address.split(' ').join('-')
		city = city.split(' ').join('-')
		url = 'http://cribspot.com/listing/' + listing_id + '/' + street_address + '-' + city + '-' + state + '-' + zip
		return url

	###
	Brings up a dialog box for user to add a message and then post to their facebook timeline
	###
	@ShareListingOnFacebook: (listing_id, street_address, city, state, zip, description=null, building_name=null) ->

		url = @GetShareUrl(listing_id, street_address, city, state, zip)
		caption = 'Check out this listing on Cribspot!'
		if building_name == null
			building_name = street_address
		else
			caption = street_address

		fbObj = 
			method: 'feed'
			link: url
			picture: 'http://www.cribspot.com/img/upright_logo.png'
			name: building_name
			caption: caption

		if description != null
			fbObj['description'] = description

		FB.ui fbObj

	@GetTwitterShareUrl: (listing_id, street_address, city, state, zip) ->
		if listing_id == null or street_address == null or city == null or state == null or zip == null
			return null
		url = @GetShareUrl(listing_id, street_address, city, state, zip)
		return 'https://twitter.com/share?url=' + encodeURIComponent(url) + '&text=' + encodeURIComponent('Check out this listing on Cribspot!') + '&via=TheCribspot'


	@InitTweetButton: (listing_id, street_address, city, state, zip) ->
		if listing_id == null or street_address == null or city == null or state == null or zip == null
			return null

		url = @GetShareUrl(listing_id, street_address, city, state, zip)
		$('#twitterDiv iframe').remove();
		tweetBtn = $('<a></a>').addClass('twitter-share-button')
		.attr('href', 'http://twitter.com/share')
		.attr('data-url', url)
		.attr('data-text','Check out this awesome property on Cribspot.com! ' + url)
		.attr('data-via', 'TheCribspot')
		$('#twitterDiv').append(tweetBtn);

		twttr.widgets.load();