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
			promotion: "tell my friends"

		fbObj = 
			method: 'feed'
			link: "https://cribspot.com/"
			picture: 'https://s3-us-west-2.amazonaws.com/cribspot-img/upright_logo.png'
			name: "Join Cribspot"
			caption: "It's a party!"
			description: "Make your life easier...use Cribspot. Search off-campus houses and apartments quickly."


		FB.ui fbObj, (response) ->
			if response?.post_id
				A2Cribs.MixPanel.Event "Social share complete",
					type: "facebook"
					element: "header"
					promotion: "tell my friends"
					post_id: response.post_id

	@FBPromotion: ->
		A2Cribs.MixPanel.Event "Social share",
			type: "facebook"
			element: "header"
			promotion: "wisconsin sunglasses"

		fbObj = 
			method: 'feed'
			link: "https://cribspot.com/"
			picture: 'https://lh4.googleusercontent.com/-JCwU1KBqw1I/UnAMzgSnPeI/AAAAAAAAAIA/ySQHQfwYGFA/w726-h545-no/sunglasses.jpg'
			name: "Free Shades for Wisconsin Students!"
			caption: "You're gonna need to protect your eyes - your off-campus housing search is now looking pretty bright."
			description: "To celebrate our recent launch at University of Wisconsin-Madison, we're giving away 5 pairs of these awesome sunglasses! Offer only valid for Wisconsin students - just share this post to qualify! We'll notify the winners on Thursday, October 31st."

		FB.ui fbObj, (response) ->
			if response?.post_id
				A2Cribs.MixPanel.Event "Social share complete",
					type: "facebook"
					element: "header"
					promotion: "wisconsin sunglasses"
					post_id: response.post_id


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

	@EmailInvite: (email_list) ->
		return $.ajax
			url: myBaseUrl + "Invitations/InviteFriends"
			type: 'POST'
			data: 
				emails: email_list
			success: ->
				A2Cribs.MixPanel.Event "Send Verify Text",
					success: true
			error: ->
				#Failed to send message
				A2Cribs.MixPanel.Event "Send Verify Text",
					success: false

	
	$("#header").ready =>
		$(".share_on_fb").click =>
			@ShareOnFacebook()

		$(".promotion_on_fb").click =>
			@FBPromotion()

	$("#email_invite").ready =>
		$("#send_email_invite").click (event) =>
			$("#send_email_invite").button "loading"
			emails = []
			$("#email_invite").find(".roommate_email").each (index, element) ->
				emails.push $(element).val()
			@EmailInvite(emails)
			.always ->
				$("#email_invite").modal "hide"
				$("#send_email_invite").button "reset"
			A2Cribs.MixPanel.Event "Email Friends",
				"number of emails": email?.length
				"action": "after signup"

		$("#email_invite").on "keyup", ".roommate_email", (event) =>
			re = /\S+@\S+\.\S+/
			if re.test $(event.currentTarget.parentElement).find(".roommate_email").val()
				$(event.currentTarget.parentElement).addClass "completed_roommate"
				return 
			$(event.currentTarget.parentElement).removeClass "completed_roommate"

		$(".add_roommate").click =>
			row_count = $("#email_invite").find(".roommate_email").last().data "roommate-count"
			email_row = $("<div class='roommate_row'><input data-roommate-count='#{row_count + 1}' class='roommate_email' type='email' placeholder='E.g. myhousem@te.com'><i class='icon-ok-sign'></i></div>")
			$("#email_invite").find(".email_invite_list").append email_row
	