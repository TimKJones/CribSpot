// Generated by CoffeeScript 1.6.3
/*
Class is for scheduling and picking a time to tour
*/


(function() {
  var Tour;

  Tour = (function() {
    var date, months, timeslots, weekdays,
      _this = this;

    function Tour() {}

    Tour.DATE_RANGE_SIZE = 3;

    Tour.current_offset = 1;

    /*
    	Object map that contains the selected timeslots
    	Hashed by string of the date_offset and timeslot
    */


    Tour.selected_timeslots = {};

    weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    timeslots = [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];

    /*
    	Private Helper function
    	Returns an object with strings for day and month
    	Today offset is zero
    */


    date = function(day_offset) {
      var offset_date, today;
      if (day_offset == null) {
        day_offset = 0;
      }
      today = new Date();
      offset_date = new Date(today.getTime() + day_offset * 24 * 60 * 60 * 1000);
      return {
        date: offset_date.getDate(),
        day: weekdays[offset_date.getDay()],
        month: months[offset_date.getMonth()],
        year: offset_date.getYear()
      };
    };

    /*
    	Listens for when the element is ready
    	Will not fire if schedule_tour div is
    	not contained on the page
    */


    $(document).ready(function() {
      if ($("#schedule_tour").length) {
        Tour.SetupCalendarUI();
        Tour.SetDates(Tour.current_offset);
        Tour.SetupInfoUI();
        return $(document).on("logged_in", function(event, user) {
          if ((user != null ? user.phone : void 0) != null) {
            $("#phone_verified").val(user.phone);
            $("#verify_phone_number").val(user.phone);
            return $("#verify_phone_btn").addClass("verified").text("Verified").prop("disabled", true);
          }
        });
      }
    });

    /*
    	SetupCalendarUI
    	Adds listeners for click objects in the
    	schedule tour element
    */


    Tour.SetupCalendarUI = function() {
      var _this = this;
      $(".time_slot").mouseenter(function(event) {
        var filler;
        filler = $(event.delegateTarget).find(".time_slot_filler");
        if ($(event.delegateTarget).hasClass("selected")) {
          filler.find("i").removeClass("icon-ok-sign").addClass("icon-remove-sign");
        }
        return filler.show();
      }).mouseleave(function(event) {
        var filler;
        filler = $(event.delegateTarget).find(".time_slot_filler");
        if ($(event.delegateTarget).hasClass("selected")) {
          return filler.show().find("i").removeClass("icon-remove-sign").addClass("icon-ok-sign");
        } else {
          return filler.hide();
        }
      }).click(function(event) {
        var filler, offset_date;
        filler = $(event.delegateTarget).find(".time_slot_filler");
        if ($(event.delegateTarget).hasClass("selected")) {
          filler.hide().find("i").removeClass("icon-remove-sign").addClass("icon-plus-sign");
          $(event.delegateTarget).removeClass("selected");
          offset_date = parseInt($(event.delegateTarget).attr("data-dateoffset"), 10);
          return _this.DeleteTimeSlot(_this.current_offset + offset_date, $(event.delegateTarget).attr("data-timeslot"));
        } else {
          filler.show().find("i").removeClass("icon-plus-sign").addClass("icon-ok-sign");
          $(event.delegateTarget).addClass("selected");
          offset_date = parseInt($(event.delegateTarget).attr("data-dateoffset"), 10);
          return _this.AddTimeSlot(_this.current_offset + offset_date, $(event.delegateTarget).attr("data-timeslot"));
        }
      });
      $("#next_date").click(function() {
        _this.current_offset += _this.DATE_RANGE_SIZE;
        _this.SetDates(_this.current_offset);
        if (_this.current_offset > _this.DATE_RANGE_SIZE) {
          return $("#prev_date").removeClass("disabled");
        }
      });
      $("#prev_date").click(function() {
        if (_this.current_offset - _this.DATE_RANGE_SIZE > 0) {
          _this.current_offset -= _this.DATE_RANGE_SIZE;
          _this.SetDates(_this.current_offset);
          if (_this.current_offset < _this.DATE_RANGE_SIZE) {
            return $("#prev_date").addClass("disabled");
          }
        }
      });
      return $("#request_times_btn").click(function() {
        if (_this.TimeSlotCount() >= 3) {
          $("#calendar_picker").hide();
          return $("#schedule_info").show();
        } else {
          A2Cribs.UIManager.CloseLogs();
          return A2Cribs.UIManager.Error("Please select at least three time slots that work for you!");
        }
      });
    };

    /*
    	Setup My Info UI
    */


    Tour.SetupInfoUI = function() {
      var _this = this;
      $("body").on("keyup", ".roommate_input", function(event) {
        var re, _ref;
        if (((_ref = $(event.currentTarget.parentElement).find(".roommate_name").val()) != null ? _ref.length : void 0) !== 0) {
          re = /\S+@\S+\.\S+/;
          if (re.test($(event.currentTarget.parentElement).find(".roommate_email").val())) {
            $(event.currentTarget.parentElement).addClass("completed_roommate");
            return;
          }
        }
        return $(event.currentTarget.parentElement).removeClass("completed_roommate");
      });
      $("#back_to_timeslots").click(function() {
        $("#schedule_info").hide();
        return $("#calendar_picker").show();
      });
      $("#add_roommate_email").click(function() {
        var email_row, row_count;
        row_count = $(".email_row").last().data("email-row");
        email_row = $("<div data-email-row='" + (row_count + 1) + "' class='row-fluid email_row'>				<input class='roommate_input roommate_name' type='text' placeholder='Name'>				<input class='roommate_input roommate_email' type='email' placeholder='Email'>				<span class='complete_email'><i class='icon-ok-sign icon-large'></i></span>			</div>");
        return $("#email_invite_list").append(email_row);
      });
      $("#verify_phone_number").keyup(function() {
        var phone;
        phone = $("#verify_phone_number").val();
        if ((phone != null ? phone.length : void 0) > 0 && phone === $("#phone_verified").val()) {
          return $("#verify_phone_btn").addClass("verified").text("Verified").prop("disabled", true);
        } else {
          return $("#verify_phone_btn").removeClass("verified").text("Click to Verify").prop("disabled", false);
        }
      });
      $("#verify_phone_btn").click(function() {
        var phone;
        phone = $("#verify_phone_number").val();
        if ((phone != null ? phone.length : void 0) !== 10 || isNaN(phone)) {
          A2Cribs.UIManager.CloseLogs();
          A2Cribs.UIManager.Error("Please enter a valid phone number");
          return;
        }
        $.ajax({
          url: myBaseUrl + "Users/SendPhoneVerificationCode",
          type: 'POST',
          data: {
            phone: phone
          }
        });
        return $("#verify_phone").modal('show');
      });
      $("#confirm_validation_code").click(function() {
        var code;
        code = $("#verification_code").val();
        if ((code != null ? code.length : void 0) !== 5) {
          A2Cribs.UIManager.CloseLogs();
          A2Cribs.UIManager.Error("Invalid Code!");
          return false;
        }
        return $.ajax({
          url: myBaseUrl + "Users/ConfirmPhoneVerificationCode",
          type: 'POST',
          data: {
            code: code
          },
          success: function(response) {
            response = JSON.parse(response);
            if (response.error != null) {
              return A2Cribs.UIManager.Error(response.error);
            } else {
              A2Cribs.UIManager.Success("Phone Number Verified!");
              $("#verify_phone").modal('hide');
              $("#phone_verified").val($("#verify_phone_number").val());
              return $("#verify_phone_btn").addClass("verified").text("Verified").prop("disabled", true);
            }
          },
          error: function() {
            A2Cribs.UIManager.CloseLogs();
            return A2Cribs.UIManager.Error("Invalid Code!");
          }
        });
      });
      return $("#complete_tour_request").click(function() {
        var phone;
        phone = $("#verify_phone_number").val();
        if ((phone != null ? phone.length : void 0) !== 10 || isNaN(phone)) {
          A2Cribs.UIManager.CloseLogs();
          A2Cribs.UIManager.Error("Please enter a valid phone number");
          return;
        }
        if ((phone != null ? phone.length : void 0) === 0 || phone !== $("#phone_verified").val()) {
          A2Cribs.UIManager.CloseLogs();
          A2Cribs.UIManager.Error("Please click the verify button to send verification text");
          return;
        }
        $("#complete_tour_request").button('loading');
        return _this.RequestTourTimes($("#listing-data").data("listing-id"), $("#tour_notes").val(), _this.GetHousematesList()).done(function(response) {
          if (response.error != null) {
            A2Cribs.UIManager.Error(response.error);
            return;
          }
          $(".schedule_page").hide();
          $("#schedule_completed").show();
          return A2Cribs.UIManager.Success("Your tour times have been received");
        }).fail(function() {
          A2Cribs.UIManager.CloseLogs();
          return A2Cribs.UIManager.Error("Failed to request tour times. Sorry. Please contact help@cribspot.com if this continues to be an issue");
        }).always(function() {
          return $("#complete_tour_request").button('reset');
        });
      });
    };

    /*
    	Get Housemates List
    	Fetches all the validated housemates email and names
    */


    Tour.GetHousematesList = function() {
      var housemates_list;
      housemates_list = [];
      $(".completed_roommate").each(function(index, element) {
        return housemates_list.push({
          name: $(element).find(".roommate_name").val(),
          email: $(element).find(".roommate_email").val()
        });
      });
      return housemates_list;
    };

    /*
    	Add Time Slot
    	Adds timeslot to selected timeslot map
    */


    Tour.AddTimeSlot = function(offset_date, time_slot) {
      var hash, today;
      hash = "" + offset_date + "-" + time_slot;
      today = new Date();
      return this.selected_timeslots[hash] = {
        date: new Date(today.getFullYear(), today.getMonth(), today.getDate() + offset_date, time_slot)
      };
    };

    /*
    	Delete Time Slot
    	Removes the timeslot from the selected timeslot
    	map
    */


    Tour.DeleteTimeSlot = function(offset_date, time_slot) {
      var hash;
      hash = "" + offset_date + "-" + time_slot;
      if (this.selected_timeslots[hash] != null) {
        return delete this.selected_timeslots[hash];
      }
    };

    /*
    	Time Slot Count
    	Returns the number of timeslots currently
    	selected
    */


    Tour.TimeSlotCount = function() {
      var count, key;
      count = 0;
      for (key in this.selected_timeslots) {
        count++;
      }
      return count;
    };

    /*
    	Request Tour Times
    	Sends a list of the date objects to
    	Tours/RequestTourTimes
    */


    Tour.RequestTourTimes = function(listing_id, note, housemates) {
      var key, time, times, _ref;
      if (note == null) {
        note = "";
      }
      times = [];
      _ref = this.selected_timeslots;
      for (key in _ref) {
        time = _ref[key];
        if (time.date != null) {
          times.push({
            date: time.date.toJSON()
          });
        }
      }
      return $.ajax({
        url: myBaseUrl + 'Tours/RequestTourTimes',
        type: 'POST',
        data: {
          times: times,
          listing_id: listing_id,
          notes: note,
          housemates: housemates
        }
      });
    };

    /*
    	Set Calendar
    	Takes an offset_date and fills in calendar UI
    	with saved timeslots in selected timeslots
    */


    Tour.SetCalendar = function(offset_date) {
      var i, timeslot, _i, _len, _results;
      this.ClearCalendar();
      _results = [];
      for (_i = 0, _len = timeslots.length; _i < _len; _i++) {
        timeslot = timeslots[_i];
        _results.push((function() {
          var _j, _ref, _results1;
          _results1 = [];
          for (i = _j = offset_date, _ref = offset_date + this.DATE_RANGE_SIZE - 1; offset_date <= _ref ? _j <= _ref : _j >= _ref; i = offset_date <= _ref ? ++_j : --_j) {
            if (this.selected_timeslots["" + i + "-" + timeslot] != null) {
              console.log("Timeslot : " + timeslot + " " + i);
              _results1.push($("#ts_" + (i - offset_date) + timeslot).addClass("selected").find(".time_slot_filler").show().find("i").removeClass("icon-plus-sign").addClass("icon-ok-sign"));
            } else {
              _results1.push(void 0);
            }
          }
          return _results1;
        }).call(this));
      }
      return _results;
    };

    /*
    	Clear Calendar
    	Clears all selected div but does not
    	remove the timeslots from the selected timeslots
    	map (purely UI)
    */


    Tour.ClearCalendar = function() {
      $(".time_slot").removeClass("selected");
      return $(".time_slot_filler").hide().find("i").removeClass("icon-remove-sign").removeClass("icon-ok-sign").addClass("icon-plus-sign");
    };

    /*
    	Set Dates
    	Load the set of three days
    */


    Tour.SetDates = function(offset_date) {
      var calendar_table_dates_html, date_range_array, date_range_string, i, _i, _ref;
      if (offset_date == null) {
        offset_date = 0;
      }
      date_range_array = [];
      calendar_table_dates_html = "<td></td>";
      for (i = _i = 0, _ref = this.DATE_RANGE_SIZE - 1; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
        date_range_array.push(date(i + offset_date));
        calendar_table_dates_html += "<th>" + date_range_array[i].day + ", " + date_range_array[i].month + " " + date_range_array[i].date + "</th>";
      }
      $("#calendar_table_dates").empty().html(calendar_table_dates_html);
      date_range_string = "" + (date_range_array[0].month.substring(0, 3)) + " " + date_range_array[0].date + "-";
      date_range_string += "" + (date_range_array[2].month.substring(0, 3)) + " " + date_range_array[2].date;
      $(".date_range").html(date_range_string);
      return this.SetCalendar(offset_date);
    };

    return Tour;

  }).call(this);

}).call(this);
