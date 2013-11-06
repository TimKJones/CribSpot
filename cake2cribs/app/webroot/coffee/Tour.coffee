###
Class is for scheduling and picking a time to tour
###
class Tour
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
		@SetupInfoUI()

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
				@DeleteTimeSlot @current_offset + offset_date, $(event.delegateTarget).attr("data-timeslot")
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
			if @TimeSlotCount() >= 3
				$("#calendar_picker").hide()
				$("#schedule_info").show()
				A2Cribs.MixPanel.Event "Request My Times",
					success: true
			else
				A2Cribs.UIManager.CloseLogs()
				A2Cribs.UIManager.Error "Please select at least three time slots that work for you!"
				A2Cribs.MixPanel.Event "Request My Times",
					success: false

	###
	Setup My Info UI
	###
	@SetupInfoUI: ->
		$("body").on "keyup", ".roommate_input", (event) =>
			if $(event.currentTarget.parentElement).find(".roommate_name").val()?.length isnt 0
				re = /\S+@\S+\.\S+/
				if re.test $(event.currentTarget.parentElement).find(".roommate_email").val()
					$(event.currentTarget.parentElement).addClass "completed_roommate"
					return 
			$(event.currentTarget.parentElement).removeClass "completed_roommate"

		$("#back_to_timeslots").click ->
			$("#schedule_info").hide()
			$("#calendar_picker").show()

		$("#add_roommate_email").click =>
			row_count = $(".email_row").last().data "email-row"
			email_row = $("<div data-email-row='#{row_count + 1}' class='row-fluid email_row'>
				<input class='roommate_input roommate_name' type='text' placeholder='Name'>
				<input class='roommate_input roommate_email' type='email' placeholder='Email'>
				<span class='complete_email'><i class='icon-ok-sign icon-large'></i></span>
			</div>")
			$("#email_invite_list").append email_row

		$("#verify_phone_number").keyup =>
			phone = $("#verify_phone_number").val()
			if phone?.length > 0 and phone is $("#phone_verified").val()
				$("#verify_phone_btn").addClass("verified").text("Verified").prop "disabled", true
			else
				$("#verify_phone_btn").removeClass("verified").text("Click to Verify").prop "disabled", false

		# Validate phone number
		$("#verify_phone_btn").click =>
			# if phone number looks legit
			phone = $("#verify_phone_number").val()
			if phone?.length isnt 10 or isNaN phone
				A2Cribs.UIManager.CloseLogs()
				A2Cribs.UIManager.Error "Please enter a valid phone number"
				return

			# Send the text
			$.ajax
				url: myBaseUrl + "Users/SendPhoneVerificationCode"
				type: 'POST'
				data: 
					phone: phone
				success: ->
					A2Cribs.MixPanel.Event "Send Verify Text",
						success: true
				error: ->
					#Failed to send message
					A2Cribs.MixPanel.Event "Send Verify Text",
						success: false
			

			$("#verify_phone").modal 'show'

		# Confirm Valiation Code
		$("#confirm_validation_code").click =>
			# if code looks legit
			code = $("#verification_code").val()

			if code?.length isnt 5
				A2Cribs.UIManager.CloseLogs()
				A2Cribs.UIManager.Error "Invalid Code!"
				return false

			# Send the text
			$.ajax
				url: myBaseUrl + "Users/ConfirmPhoneVerificationCode"
				type: 'POST'
				data: 
					code: code

				success: (response) ->
					response = JSON.parse response
					if response.error?
						A2Cribs.UIManager.Error response.error
						A2Cribs.MixPanel.Event "Phone verify completed",
							success: false
					else
						A2Cribs.UIManager.Success "Phone Number Verified!"
						$("#verify_phone").modal 'hide'
						$("#phone_verified").val $("#verify_phone_number").val()
						$("#verify_phone_btn").addClass("verified").text("Verified").prop "disabled", true
						A2Cribs.MixPanel.Event "Phone verify completed",
							success: true

				error: ->
					A2Cribs.UIManager.CloseLogs()
					A2Cribs.UIManager.Error "Invalid Code!"

		# To be completed
		$("#complete_tour_request").click =>
			# if phone number is verified
			phone = $("#verify_phone_number").val()
			if phone?.length isnt 10 or isNaN phone
				A2Cribs.UIManager.CloseLogs()
				A2Cribs.UIManager.Error "Please enter a valid phone number"
				return
			if phone?.length is 0 or phone isnt $("#phone_verified").val()
				A2Cribs.UIManager.CloseLogs()
				A2Cribs.UIManager.Error "Please click the verify button to send verification text"
				return

			$("#complete_tour_request").button 'loading'

			@RequestTourTimes($("#listing-data").data("listing-id"), $("#tour_notes").val(), @GetHousematesList())
			.done (response) ->
				if response.error?
					A2Cribs.UIManager.Error response.error
					return
				$(".schedule_page").hide()
				$("#schedule_completed").show()
				A2Cribs.UIManager.Success "Your tour times have been received"
				A2Cribs.MixPanel.Event "Finished Tour",
					success: true
			.fail ->
				A2Cribs.UIManager.CloseLogs()
				A2Cribs.UIManager.Error "Failed to request tour times. Sorry. Please contact help@cribspot.com if this continues to be an issue"
				A2Cribs.MixPanel.Event "Finished Tour",
					success: false
			.always ->
				$("#complete_tour_request").button 'reset'

	###
	Get Housemates List
	Fetches all the validated housemates email and names
	###
	@GetHousematesList: ->
		housemates_list = []
		$(".completed_roommate").each (index, element) ->
			housemates_list.push
				name: $(element).find(".roommate_name").val()
				email: $(element).find(".roommate_email").val()
		return housemates_list

	###
	Add Time Slot
	Adds timeslot to selected timeslot map
	###					
	@AddTimeSlot: (offset_date, time_slot) ->
		hash = "#{offset_date}-#{time_slot}"
		today = new Date()
		@selected_timeslots[hash] =
			date: new Date(today.getFullYear(), today.getMonth(), today.getDate() + offset_date, time_slot)

	###
	Delete Time Slot
	Removes the timeslot from the selected timeslot
	map
	###
	@DeleteTimeSlot: (offset_date, time_slot) ->
		hash = "#{offset_date}-#{time_slot}"
		if @selected_timeslots[hash]?
			delete @selected_timeslots[hash]

	###
	Time Slot Count
	Returns the number of timeslots currently
	selected
	###
	@TimeSlotCount: ->
		count = 0
		for key of @selected_timeslots
			count++
		return count

	###
	Request Tour Times
	Sends a list of the date objects to
	Tours/RequestTourTimes
	###
	@RequestTourTimes: (listing_id, note = "", housemates) ->
		times = []
		for key, time of @selected_timeslots
			times.push time
		return $.ajax
			url: myBaseUrl + 'Tours/RequestTourTimes'
			type: 'POST'
			data:
				times: times
				listing_id: listing_id
				notes: note
				housemates: housemates

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
				if @selected_timeslots["#{i}-#{timeslot}"]?
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
