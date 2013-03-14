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
				if content_header.next('.drop-down').length > 0
					#Toggle Drop down
					show_content = content_header.next('.drop-down').is ':hidden'
					@SlideDropDown content_header, show_content	
				else
					@ShowContent content, true

			content_header.next?('.drop-down')
				.find('.drop-down-list').click =>
					@ShowContent content			

	@SizeContent:()->
		# Strech the widget to the bottom of the window
		main_content = $('#main_content')
		middle_content = $('#middle_content')
		main_content.css 'height', (window.innerHeight - main_content.offset().top) + 'px'

		# content_header is the jquery object for the side content headers that HAS a
		# drop down sibling. mode is need to be either ['toggle', 'hide', 'show']
	@SlideDropDown:(content_header, show_content)->
		
		dropdown = content_header.next('.drop-down')
		if dropdown.length == 0
			return

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

	@ShowContent:(content)->
		content.siblings().addClass 'hidden'
		content.removeClass 'hidden'

	@Direct: (directive)->
		content_header = $('#' + directive.classname + "-content-header")
		content_header.trigger 'click'
		if directive.data?
			@ShowContent($('.' + directive.classname + "-content"))
