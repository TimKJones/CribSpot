class A2Cribs.SubletOwner
	# constructor: (@FirstName, @facebook_userid, @VerifiedUniversity, @TwitterFollowers) ->

	constructor: (user)->

		@FirstName = user.first_name
		@facebook_userid = user.facebook_userid
		@VerifiedUniversity = user.verified_university
		@university_verified = user.university_verified
		@twitter_userid = user.twitter_userid
		@verified = user.verified
		@id = user.id