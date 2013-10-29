class A2Cribs.Landing

	swap_backgrounds = (university_id) ->
		old_background = $(".current_background")
		if old_background.attr("data-university") isnt university_id
			new_background = $("img[data-university='#{university_id}'].school_background")
			new_background.css("opacity", "0.0").zIndex(-1).addClass "current_background"
			old_background.zIndex(-2).removeClass "current_background"
			new_background.animate
				"opacity": 1.0
			, 1200, () ->
				old_background.zIndex(-3)

	set_school = (university) ->
		for key,val of university['University']
			$("#school_page").find(".#{key}").hide().text(val).fadeIn()

		swap_backgrounds university['University']['id']

		url_name = university['University']['name'].split(" ").join("_")

		$("#map_link").attr "href", "/rental/#{url_name}"

		$(".background_source").attr "href", university['University']['background_source']

		$(".school_logo").css "background-image", "url(#{university['University']['logo_path']})"

		$(".founder_photo").attr "src", university.University.founder_image

		$(".founder_title").text "#{university['University']['name']} Founder"


	@Init: (@locations) ->
		$(window).scroll () ->
			scrolled = $(window).scrollTop()
			$('.current_background').css('top',(0 - (scrolled * .25)) + 'px');

		$("#friends_invite").click () ->
			A2Cribs.MixPanel.Event "Invite Friends", null
			FB?.ui
				method: 'apprequests',
				message: 'Join the Movement. All the College Rentals. All in One Spot.'
			, (response) ->
				A2Cribs.MixPanel.Event "Invite Friends completed", null

		if @locations?.length?
			random_school = Math.floor((Math.random() * @locations.length))
			set_school @locations[random_school]

		$(".university_link").click (event) =>
			university_id = $(event.delegateTarget).attr "data-university"
			for university in @locations
				if university['University']['id'] is university_id
					@Current_University = university
					break
			if @Current_University?
				set_school @Current_University

			$('html, body').animate
				scrollTop: $("#school_page").offset().top
			, 1200
		
