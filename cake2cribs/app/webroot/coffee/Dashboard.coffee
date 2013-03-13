class A2Cribs.Dashboard
	
	@SetupUI:()->
		$(window).resize =>
			@SizeContent()
		@SizeContent()

		# Set up handlers to handle content drop downs and content switching
		$('.content-header').each (index, element)=>
			content_header = $(element)
			class_name = content_header.attr 'classname'
			content = $('.' + class_name + '-content')
			
			$(element).click (event)=>
				if $(event.target).hasClass 'toggle-drop-down'
					# the drop down toggle was clicked
					# Just toggle the dropdown for this header
					show_content = content_header.next('.drop-down').is ':hidden'
					@SlideDropDown content_header, show_content
				else
					# Some other part of the header was clicked so
					# if that headers middle/right content is not visible 
					# display it as well as expand the drop down

					@SlideDropDown content_header, true
					@ShowHideContent content, true

			content_header.next?('.drop-down')
				.find('.drop-down-list').click =>
					@ShowHideContent content, true				

	@SizeContent:()->
		# Strech the widget to the bottom of the window
		main_content = $('#main_content')
		middle_content = $('#middle_content')
		main_content.css 'height', (window.innerHeight - main_content.offset().top) + 'px'

		# content_header is the jquery object for the side content headers that HAS a
		# drop down sibling. mode is need to be either ['toggle', 'hide', 'show']
	@SlideDropDown:(content_header, show_content)->
		
		dropdown = content_header.next('.drop-down')
		toggle_icon = content_header.children('i')[0] #arrow icon
		$(toggle_icon)
			.toggleClass('icon-caret-right', !show_content)
			.toggleClass('icon-caret-down', show_content)

		
		$(content_header)
			.toggleClass('shadowed', show_content)
			.toggleClass('expanded', show_content)
			.toggleClass('minimized', !show_content)

		if show_content
			dropdown.slideDown 'fast'
		else
			dropdown.slideUp 'fast'

	@ShowHideContent:(content, show)->
		content.toggleClass 'hidden', !show
