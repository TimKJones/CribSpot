###
Class is for scheduling and picking a time to tour
###
class A2Cribs.Tour
	@DATE_RANGE_SIZE = 3
	@current_offset = 1

	###
	Object map that contains the selected timeslots
	Hashed by string of the date_offset and timeslot
	###
	@selected_timeslots = {}

	weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
	months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
	timeslots = [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]

	###
	Private Helper function
	Returns an object with strings for day and month
	Today offset is zero
	###
	date = (day_offset = 0) ->
		today = new Date()
		offset_date = new Date today.getTime() + day_offset * 24 * 60 * 60 * 1000
		return {
			date: offset_date.getDate()
			day: weekdays[offset_date.getDay()]
			month: months[offset_date.getMonth()]
			year: offset_date.getYear()
		}

	###
	Listens for when the element is ready
	Will not fire if schedule_tour div is
	not contained on the page
	###
	$("#schedule_tour").ready =>
		@SetupCalendarUI()
		@SetDates @current_offset

	###
	SetupCalendarUI
	Adds listeners for click objects in the
	schedule tour element
	###
	@SetupCalendarUI: ->
		$(".time_slot")
		.mouseenter (event) ->
			filler = $(event.delegateTarget).find(".time_slot_filler")
			if $(event.delegateTarget).hasClass "selected"
				# Change the icon to remove
				filler.find("i")
				.removeClass("icon-ok-sign")
				.addClass("icon-remove-sign")

			filler.show()

		.mouseleave (event) ->
			filler = $(event.delegateTarget).find(".time_slot_filler")
			if $(event.delegateTarget).hasClass "selected"
				# Change the icon to ok
				filler.show().find("i")
				.removeClass("icon-remove-sign")
				.addClass("icon-ok-sign")
			else
				filler.hide()

		.click (event) =>
			filler = $(event.delegateTarget).find(".time_slot_filler")
			if $(event.delegateTarget).hasClass "selected"
				# Change the icon to ok
				filler.hide().find("i")
				.removeClass("icon-remove-sign")
				.addClass("icon-plus-sign")
				$(event.delegateTarget).removeClass("selected")
				offset_date = parseInt $(event.delegateTarget).attr("data-dateoffset"), 10
				@AddTimeSlot @current_offset + offset_date, $(event.delegateTarget).attr("data-timeslot")
			else
				filler.show().find("i")
				.removeClass("icon-plus-sign")
				.addClass("icon-ok-sign")
				$(event.delegateTarget).addClass("selected")
				offset_date = parseInt $(event.delegateTarget).attr("data-dateoffset"), 10
				@AddTimeSlot @current_offset + offset_date, $(event.delegateTarget).attr("data-timeslot")

		$("#next_date").click () =>
			@current_offset += @DATE_RANGE_SIZE
			@SetDates @current_offset
			if @current_offset > @DATE_RANGE_SIZE
				$("#prev_date").removeClass "disabled"

		$("#prev_date").click () =>
			if @current_offset - @DATE_RANGE_SIZE > 0
				@current_offset -= @DATE_RANGE_SIZE
				@SetDates @current_offset
				if @current_offset < @DATE_RANGE_SIZE
					$("#prev_date").addClass "disabled"

		$("#request_times_btn").click () =>
			# Validate that there are at least three time slots
			# selected
			$("#calendar_picker").hide()
			$("#schedule_info").show()

	###
	Setup My Info UI
	###
	@SetupInfoUI: ->
		# To be completed


	###
	Add Time Slot
	Adds timeslot to selected timeslot map
	###					
	@AddTimeSlot: (offset_date, time_slot) ->
		hash = "#{offset_date}#{time_slot}"
		today = new Date()
		@selected_timeslots[hash] =
			date: new Date(today.getFullYear(), today.getMonth(), today.getDate() + offset_date, time_slot)

	###
	Delete Time Slot
	Removes the timeslot from the selected timeslot
	map
	###
	@DeleteTimeSlot: (offset_date, time_slot) ->
		hash = "#{offset_date} + #{time_slot}"
		if @selected_timeslots[hash]?
			delete @selected_timeslots[hash]

	###
	Request Tour Times
	Sends a list of the date objects to
	Tours/RequestTourTimes
	###
	@RequestTourTimes: ->
		times = []
		for key, time of @selected_timeslots
			times.push time
		$.ajax
			url: myBaseUrl + 'Tours/RequestTourTimes'
			type: 'POST'
			data:
				times: times
				listing_id: 1
				notes: "This is a note"
			success: (response) ->
				alert response

	###
	Set Calendar
	Takes an offset_date and fills in calendar UI
	with saved timeslots in selected timeslots
	###
	@SetCalendar: (offset_date) ->
		@ClearCalendar()
		# Loop through selected timeslots
		for timeslot in timeslots
			for i in [offset_date..offset_date + @DATE_RANGE_SIZE - 1]
				if @selected_timeslots["#{i}#{timeslot}"]?
					console.log "Timeslot : #{timeslot} #{i}"
					$("#ts_#{i - offset_date}#{timeslot}")
					.addClass("selected")
					.find(".time_slot_filler")
					.show().find("i")
					.removeClass("icon-plus-sign")
					.addClass("icon-ok-sign")

	###
	Clear Calendar
	Clears all selected div but does not
	remove the timeslots from the selected timeslots
	map (purely UI)
	###
	@ClearCalendar: ->
		$(".time_slot").removeClass "selected"
		$(".time_slot_filler")
		.hide().find("i")
		.removeClass("icon-remove-sign")
		.removeClass("icon-ok-sign")
		.addClass("icon-plus-sign")

	###
	Set Dates
	Load the set of three days
	###
	@SetDates: (offset_date = 0) ->
		date_range_array = []
		calendar_table_dates_html = "<td></td>"
		for i in [0..@DATE_RANGE_SIZE - 1]
			date_range_array.push date(i + offset_date)
			calendar_table_dates_html += "<th>#{date_range_array[i].day}, #{date_range_array[i].month} #{date_range_array[i].date}</th>"

		$("#calendar_table_dates").empty().html calendar_table_dates_html
		date_range_string = "#{date_range_array[0].month.substring(0, 3)} #{date_range_array[0].date}-"
		date_range_string += "#{date_range_array[2].month.substring(0, 3)} #{date_range_array[2].date}"
		$(".date_range").html date_range_string
		@SetCalendar offset_date
