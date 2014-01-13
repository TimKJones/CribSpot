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

		if university['University']['logo_path']?.length
			$(".school_logo").show().css "background-image", "url(#{university['University']['logo_path']})"
		else
			$(".school_logo").hide()

		if university.University.founder_image?.length
			$(".founder_photo").attr "src", university.University.founder_image
			$(".founder_title").text "#{university['University']['name']} Founder"
			$("#founder_box").fadeIn()
		else
			$("#founder_box").hide()


	@Init: (@locations) ->
		$(window).scroll () ->
			scrolled = $(window).scrollTop()
			$('.current_background').css('top',(0 - (scrolled * .25)) + 'px')

		$("#friends_invite").click () ->
			A2Cribs.ShareManager.ShowShareModal("",
			"College housing sucks! Tell your friends how easy finding the perfect house can be. Share Cribspot!",
			"landing page share"
			)
			
		if @locations?.length?
			random_school = Math.floor((Math.random() * @locations.length))
			set_school @locations[random_school]

		$(".mobile_selector").change (event) =>
			university_id = $(event.currentTarget).val()
			for university in @locations
				if university['University']['id'] is university_id
					url_name = university['University']['name'].split(" ").join("_")
					window.location.href = "/rental/#{url_name}"

		$("#school_selector").change (event) =>
			university_id = $(event.currentTarget).val()
			for university in @locations
				if university['University']['id'] is university_id
					temp_school = university
					@Current_University = university
					break
			if temp_school?
				set_school @Current_University

			$('html, body').animate
				scrollTop: $("#school_page").offset().top
			, 1200
		
