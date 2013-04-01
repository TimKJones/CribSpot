(function() {

  A2Cribs.SubletEdit = (function() {

    function SubletEdit() {}

    SubletEdit.Init = function(subletData) {
      A2Cribs.Cache.SubletEditInProgress = new A2Cribs.SubletInProgress();
      return A2Cribs.SubletEdit.InitLoadedSubletData();
    };

    SubletEdit.CacheStep1Data = function() {
      A2Cribs.Cache.SubletEditInProgress.Sublet.university_id = parseInt(A2Cribs.CorrectMarker.SelectedUniversity.id);
      A2Cribs.Cache.SubletEditInProgress.Sublet.university_name = $('#universityName').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.unit_number = $('#SubletUnitNumber').val();
      A2Cribs.Cache.SubletEditInProgress.Marker.street_address = $("#formattedAddress").val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.building_type_id = parseInt($('#SubletBuildingTypeId').val());
      A2Cribs.Cache.SubletEditInProgress.Marker.building_type_id = parseInt($('#SubletBuildingTypeId').val());
      A2Cribs.Cache.SubletEditInProgress.Marker.alternate_name = $('#SubletName').val();
      A2Cribs.Cache.SubletEditInProgress.Marker.latitude = $('#updatedLat').val();
      A2Cribs.Cache.SubletEditInProgress.Marker.longitude = $('#updatedLong').val();
      A2Cribs.Cache.SubletEditInProgress.Marker.city = $('#city').val();
      A2Cribs.Cache.SubletEditInProgress.Marker.state = $('#state').val();
      return A2Cribs.Cache.SubletEditInProgress.Marker.zip = $('#postal').val();
    };

    SubletEdit.CacheStep2Data = function() {
      A2Cribs.Cache.SubletEditInProgress.Sublet.date_begin = A2Cribs.SubletEdit.GetMysqlDateFormat($('#SubletDateBegin').val());
      A2Cribs.Cache.SubletEditInProgress.Sublet.date_end = A2Cribs.SubletEdit.GetMysqlDateFormat($('#SubletDateEnd').val());
      A2Cribs.Cache.SubletEditInProgress.Sublet.flexible_dates = $('#SubletFlexibleDates').is(':checked');
      A2Cribs.Cache.SubletEditInProgress.Sublet.number_bedrooms = $('#SubletNumberBedrooms').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.price_per_bedroom = $('#SubletPricePerBedroom').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.short_description = $('#SubletDescription').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.description = $('#SubletDescription').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.number_bathrooms = $('#SubletNumberBathrooms').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.bathroom_type_id = $('#SubletBathroomTypeId').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.utility_type_id = $('#SubletUtilityTypeId').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.utility_cost = $('#SubletUtilityCost').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.parking = $('#SubletParking').is(':checked');
      A2Cribs.Cache.SubletEditInProgress.Sublet.ac = $('#SubletAc').is(':checked');
      A2Cribs.Cache.SubletEditInProgress.Sublet.furnished_type_id = $('#SubletFurnishedTypeId').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.deposit_amount = $('#SubletDepositAmount').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.additional_fees_description = $('#SubletAdditionalFeesDescription').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.additional_fees_amount = $('#SubletAdditionalFeesAmount').val();
      return A2Cribs.Cache.SubletEditInProgress.Sublet.payment_type_id = 1;
    };

    SubletEdit.CacheStep3Data = function() {
      A2Cribs.Cache.SubletEditInProgress.Housemate.quantity = $("#HousemateQuantity").val();
      A2Cribs.Cache.SubletEditInProgress.Housemate.enrolled = $("#HousemateEnrolled").is(':checked');
      A2Cribs.Cache.SubletEditInProgress.Housemate.student_type_id = $("#HousemateStudentTypeId").val();
      A2Cribs.Cache.SubletEditInProgress.Housemate.major = $("#HousemateMajor").val();
      A2Cribs.Cache.SubletEditInProgress.Housemate.seeking = $("#HousemateSeeking").val();
      A2Cribs.Cache.SubletEditInProgress.Housemate.gender_type_id = $("#HousemateGenderTypeId").val();
      return A2Cribs.Cache.SubletEditInProgress.Housemate.type = "Sophomore";
    };

    /*
    	Populates fields in step 1 with data loaded from cache
    */

    SubletEdit.InitStep1 = function() {
      var subletData;
      subletData = A2Cribs.Cache.SubletEditInProgress;
      if (subletData.Sublet !== null && subletData.Sublet !== void 0) {
        $('#universityName').val(subletData.Sublet.university_name);
        $('#SubletUnitNumber').val(subletData.Sublet.unit_number);
      }
      if (subletData.Marker !== null && subletData.Marker !== void 0) {
        $('#SubletBuildingTypeId').val(subletData.Marker.building_type_id);
        $('#SubletName').val(subletData.Marker.alternate_name);
        $("#addressToMark").val(subletData.Marker.street_address);
        $("#formattedAddress").val(subletData.Marker.street_address);
        $('#updatedLat').val(subletData.Marker.latitude);
        $('#updatedLong').val(subletData.Marker.longitude);
        $("#city").val(subletData.Marker.city);
        $("#state").val(subletData.Marker.state);
        $("#postal").val(subletData.Marker.zip);
      }
      if (subletData.Sublet.university_name !== null && subletData.Sublet.university_name !== void 0) {
        A2Cribs.CorrectMarker.FindSelectedUniversity();
      }
      if (subletData.Marker.street_address !== null && subletData.Marker.street_address !== void 0) {
        return A2Cribs.CorrectMarker.FindAddress();
      }
    };

    SubletEdit.InitStep2 = function() {
      var beginDate, endDate, formattedBeginDate, formattedEndDate;
      if (A2Cribs.Cache.SubletEditInProgress.Sublet === null || A2Cribs.Cache.SubletEditInProgress.Sublet === void 0) {
        return;
      }
      beginDate = new Date(A2Cribs.Cache.SubletEditInProgress.Sublet.date_begin);
      formattedBeginDate = A2Cribs.SubletAdd.GetFormattedDate(beginDate);
      endDate = new Date(A2Cribs.Cache.SubletEditInProgress.Sublet.date_end);
      formattedEndDate = A2Cribs.SubletAdd.GetFormattedDate(endDate);
      $('#SubletDateBegin').val(formattedBeginDate);
      $('#SubletDateEnd').val(formattedEndDate);
      $('#SubletFlexibleDates').val(A2Cribs.Cache.SubletEditInProgress.Sublet.flexible_dates);
      $('#SubletNumberBedrooms').val(A2Cribs.Cache.SubletEditInProgress.Sublet.number_bedrooms);
      $('#SubletPricePerBedroom').val(A2Cribs.Cache.SubletEditInProgress.Sublet.price_per_bedroom);
      $('#SubletDescription').val(A2Cribs.Cache.SubletEditInProgress.Sublet.description);
      $('#SubletNumberBathrooms').val(A2Cribs.Cache.SubletEditInProgress.Sublet.number_bathrooms);
      $('#SubletBathroomTypeId').val(A2Cribs.Cache.SubletEditInProgress.Sublet.bathroom_type_id);
      $('#SubletUtilityTypeId').val(A2Cribs.Cache.SubletEditInProgress.Sublet.utility_type_id);
      $('#SubletUtilityCost').val(A2Cribs.Cache.SubletEditInProgress.Sublet.utility_type_id);
      $('#SubletParking').val(A2Cribs.Cache.SubletEditInProgress.Sublet.parking);
      $('#SubletAc').val(A2Cribs.Cache.SubletEditInProgress.Sublet.ac);
      $('#SubletFurnishedTypeId').val(A2Cribs.Cache.SubletEditInProgress.Sublet.furnished_type_id);
      $('#SubletDepositAmount').val(A2Cribs.Cache.SubletEditInProgress.Sublet.deposit_amount);
      $('#SubletAdditionalFeesDescription').val(A2Cribs.Cache.SubletEditInProgress.Sublet.additional_fees_description);
      return $('#SubletAdditionalFeesAmount').val(A2Cribs.Cache.SubletEditInProgress.Sublet.additional_fees_amount);
    };

    SubletEdit.InitStep3 = function() {
      if (A2Cribs.Cache.SubletEditInProgress.Housemate === null || A2Cribs.Cache.SubletEditInProgress.Housemate === void 0) {
        return;
      }
      $("#HousemateQuantity").val(A2Cribs.Cache.SubletEditInProgress.Housemate.quantity);
      $("#HousemateEnrolled_").val(A2Cribs.Cache.SubletEditInProgress.Housemate.enrolled);
      $("#HousemateStudentTypeId").val(A2Cribs.Cache.SubletEditInProgress.Housemate.student_type_id);
      $("#HousemateMajor").val(A2Cribs.Cache.SubletEditInProgress.Housemate.major);
      $("#HousemateSeeking").val(A2Cribs.Cache.SubletEditInProgress.Housemate.seeking);
      $("#HousemateGenderTypeId").val(A2Cribs.Cache.SubletEditInProgress.Housemate.gender_type_id);
      return $("#HousemateType").val(A2Cribs.Cache.SubletEditInProgress.Housemate.type);
    };

    /*
    	Fully populates A2Cribs.Cache.SubletData with data loaded from database
    	Call from edit view
    */

    SubletEdit.InitLoadedSubletData = function() {
      var b, h, m, s, u;
      if (A2Cribs.Cache.SubletData === void 0) return;
      s = A2Cribs.Cache.SubletData.Sublet;
      h = A2Cribs.Cache.SubletData.Housemate[0];
      m = A2Cribs.Cache.SubletData.Marker;
      u = A2Cribs.Cache.SubletData.University;
      b = A2Cribs.Cache.SubletData.BuildingType;
      if (u !== null && u !== void 0) {
        A2Cribs.Cache.SubletEditInProgress.Sublet.university_id = parseInt(u.id);
        A2Cribs.Cache.SubletEditInProgress.Sublet.university_name = u.name;
      }
      if (b !== null && b !== void 0) {
        A2Cribs.Cache.SubletEditInProgress.Sublet.building_type_id = parseInt(b.id);
      }
      if (s !== null && s !== void 0) {
        A2Cribs.Cache.SubletEditInProgress.Sublet.id = parseInt(s.id);
        A2Cribs.Cache.SubletEditInProgress.Sublet.date_begin = s.date_begin;
        A2Cribs.Cache.SubletEditInProgress.Sublet.date_end = s.date_end;
        A2Cribs.Cache.SubletEditInProgress.Sublet.number_bedrooms = parseInt(s.number_bedrooms);
        A2Cribs.Cache.SubletEditInProgress.Sublet.price_per_bedroom = parseInt(s.price_per_bedroom);
        A2Cribs.Cache.SubletEditInProgress.Sublet.payment_type_id = parseInt(s.payment_type_id);
        A2Cribs.Cache.SubletEditInProgress.Sublet.short_description = s.short_description;
        A2Cribs.Cache.SubletEditInProgress.Sublet.description = s.description;
        A2Cribs.Cache.SubletEditInProgress.Sublet.number_bathrooms = parseInt(s.number_bathrooms);
        A2Cribs.Cache.SubletEditInProgress.Sublet.utility_cost = parseInt(s.utility_cost);
        A2Cribs.Cache.SubletEditInProgress.Sublet.deposit_amount = s.deposit_amount;
        A2Cribs.Cache.SubletEditInProgress.Sublet.additional_fees_description = s.additional_fees_description;
        A2Cribs.Cache.SubletEditInProgress.Sublet.additional_fees_amount = s.additional_fees_amount;
        A2Cribs.Cache.SubletEditInProgress.Sublet.unit_number = s.unit_number;
        A2Cribs.Cache.SubletEditInProgress.Sublet.flexible_dates = s.flexible_dates;
        A2Cribs.Cache.SubletEditInProgress.Sublet.furnished_type_id = s.furnished_type_id;
        A2Cribs.Cache.SubletEditInProgress.Sublet.ac = s.ac;
        A2Cribs.Cache.SubletEditInProgress.Sublet.parking = s.parking;
      }
      if (A2Cribs.Cache.SubletData.BathroomType !== null && A2Cribs.Cache.SubletData.BathroomType !== void 0) {
        A2Cribs.Cache.SubletEditInProgress.Sublet.bathroom_type_id = parseInt(A2Cribs.Cache.SubletData.BathroomType.id);
      }
      if (b !== null && b !== void 0) {
        A2Cribs.Cache.SubletEditInProgress.Sublet.building_type_id = parseInt(b.id);
      }
      if (A2Cribs.Cache.SubletData.UtilityType !== null && A2Cribs.Cache.SubletData.UtilityType !== void 0) {
        A2Cribs.Cache.SubletEditInProgress.Sublet.utility_type_id = parseInt(A2Cribs.Cache.SubletData.UtilityType.id);
      }
      if (m !== null && m !== void 0) {
        A2Cribs.Cache.SubletEditInProgress.Marker.marker_id = parseInt(m.marker_id);
        A2Cribs.Cache.SubletEditInProgress.Marker.street_address = m.street_address;
        A2Cribs.Cache.SubletEditInProgress.Marker.building_type_id = m.building_type_id;
        A2Cribs.Cache.SubletEditInProgress.Marker.alternate_name = m.alternate_name;
        A2Cribs.Cache.SubletEditInProgress.Marker.city = m.city;
        A2Cribs.Cache.SubletEditInProgress.Marker.state = m.state;
        A2Cribs.Cache.SubletEditInProgress.Marker.zip = m.zip;
        A2Cribs.Cache.SubletEditInProgress.Marker.latitude = m.latitude;
        A2Cribs.Cache.SubletEditInProgress.Marker.longitude = m.longitude;
      }
      if (h !== null && h !== void 0) {
        A2Cribs.Cache.SubletEditInProgress.Housemate.id = parseInt(h.id);
        A2Cribs.Cache.SubletEditInProgress.Housemate.enrolled = h.enrolled;
        A2Cribs.Cache.SubletEditInProgress.Housemate.student_type_id = h.student_type_id;
        A2Cribs.Cache.SubletEditInProgress.Housemate.major = h.major;
        A2Cribs.Cache.SubletEditInProgress.Housemate.seeking = h.seeking;
        A2Cribs.Cache.SubletEditInProgress.Housemate.gender_type_id = h.gender_type_id;
        A2Cribs.Cache.SubletEditInProgress.Housemate.type = h.type;
        return A2Cribs.Cache.SubletEditInProgress.Housemate.quantity = h.quantity;
      }
    };

    /*
    	Replaces '/' with '-' to make convertible to mysql datetime format
    */

    SubletEdit.GetMysqlDateFormat = function(dateString) {
      var beginDateFormatted, date, day, month, year;
      date = new Date(dateString);
      month = date.getMonth() + 1;
      if (month < 10) month = "0" + month;
      day = date.getDate();
      if (day < 10) day = "0" + day;
      year = date.getUTCFullYear();
      return beginDateFormatted = year + "-" + month + "-" + day;
    };

    return SubletEdit;

  })();

}).call(this);
