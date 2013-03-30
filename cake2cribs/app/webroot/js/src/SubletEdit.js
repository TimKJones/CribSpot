(function() {

  A2Cribs.SubletEdit = (function() {

    function SubletEdit() {}

    SubletEdit.Init = function(subletData) {
      return A2Cribs.Cache.SubletEditInProgress = new A2Cribs.SubletInProgress();
    };

    SubletEdit.SaveStep1 = function() {};

    SubletEdit.SaveStep2 = function() {};

    SubletEdit.InitStep1 = function() {
      var subletData;
      if (A2Cribs.Cache.SubletData === void 0) return;
      subletData = A2Cribs.Cache.SubletData;
      $('#universityName').val(subletData.University.name);
      $('#SubletBuildingTypeId').val(subletData.BuildingType.name);
      $('#SubletName').val(subletData.Marker.alternate_name);
      $('#SubletUnitNumber').val(subletData.Sublet.unit_number);
      $("#addressToMark").val(subletData.Marker.street_address);
      $("#formattedAddress").val(subletData.Marker.street_address);
      $('#updatedLat').val(subletData.Marker.latitude);
      $('#updatedLong').val(subletData.Marker.longitude);
      $("#city").val(subletData.Marker.city);
      $("#state").val(subletData.Marker.state);
      $("#postal").val(subletData.Marker.zip);
      A2Cribs.CorrectMarker.FindSelectedUniversity();
      return A2Cribs.CorrectMarker.FindAddress();
    };

    SubletEdit.InitStep2 = function(subletData) {};

    return SubletEdit;

  })();

}).call(this);
