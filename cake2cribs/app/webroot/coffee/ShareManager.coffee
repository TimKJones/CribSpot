class A2Cribs.ShareManager

	###
	Creates a listing url from its individual components
	###
	@GetShareUrl: (listing_id, street_address, city, state, zip) ->
		if listing_id == null or street_address == null or city == null or state == null or zip == null
			return null
		street_address = street_address.split(' ').join('-')
		city = city.split(' ').join('-')
		url = 'https://cribspot.com/listing/' + listing_id
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
			picture: 'https://s3-us-west-2.amazonaws.com/cribspot-img/upright_logo.png'
			name: building_name
			caption: caption

		if description != null
			fbObj['description'] = description

		FB.ui fbObj

	###
	Shares the school page on facebook
	###
	@ShareOnFacebook: ->

		A2Cribs.MixPanel.Event "Social share",
			type: "facebook"
			element: "header"

		fbObj = 
			method: 'feed'
			link: "https://cribspot.com/"
			picture: 'https://s3-us-west-2.amazonaws.com/cribspot-img/upright_logo.png'
			name: "Join Cribspot"
			caption: "It's a party!"
			description: "Make your life easier...use Cribspot. Search off-campus houses and apartments quickly."

		FB.ui fbObj


	@CopyListingUrl: (listing_id, street_address, city, state, zip) ->
		url = @GetShareUrl(listing_id, street_address, city, state, zip)
		window.prompt "Copy to clipboard: Ctrl+C, Enter", url

	@ShareListingOnTwitter: (listing_id, street_address, city, state, zip) ->
		url = @GetTwitterShareUrl listing_id, street_address, city, state, zip
		# Center popup based on the screen size
		x = screen.width / 2 - 600 / 2
		y = screen.height / 2 - 350 / 2
		window.open url, 'winname', "directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=350,top=#{y},left=#{x}"

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
		.attr('href', 'https://twitter.com/share')
		.attr('data-url', url)
		.attr('data-text','Check out this awesome property on Cribspot.com! ' + url)
		.attr('data-via', 'TheCribspot')
		$('#twitterDiv').append(tweetBtn);

		twttr.widgets.load();

	$("#header").ready =>
		$(".share_on_fb").click =>
			@ShareOnFacebook()