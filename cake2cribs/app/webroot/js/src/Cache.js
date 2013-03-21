(function() {

  A2Cribs.Cache = (function() {

    function Cache() {}

    Cache.IdToSubletMap = [];

    Cache.IdToMarkerMap = [];

    Cache.IdToUniversityMap = [];

    Cache.IdToHousematesMap = [];

    Cache.SubletIdToHousemateIdsMap = [];

    Cache.MarkerIdToHoverDataMap = [];

    Cache.MarkerIdToSubletIdsMap = [];

    Cache.IdToMarkerMap = [];

    Cache.AddressToMarkerIdMap = [];

    Cache.BuildingIdToNameMap = [];

    Cache.BathroomIdToNameMap = [];

    /*
    	Add list of sublets to cache
    */

    Cache.CacheSublet = function(sublet) {
      var l;
      l = sublet;
      l.id = parseInt(l.id);
      l.marker_id = parseInt(l.marker_id);
      this.MarkerIdToSubletIdsMap[parseInt(sublet.marker_id)].push(l.id);
      l.number_bedrooms = parseInt(l.number_bedrooms);
      l.price_per_bedroom = parseInt(l.price_per_bedroom);
      l.number_bedrooms = parseInt(l.number_bedrooms);
      l.number_bathrooms = parseInt(l.number_bathrooms);
      l.utility_cost = parseInt(l.utility_cost);
      l.deposit_amount = parseInt(l.deposit_amount);
      l.additional_fees_amount = parseInt(l.additional_fees_amount);
      l.marker_id = parseInt(l.marker_id);
      l.furnished_type_id = parseInt(l.furnished_type_id);
      l.building_type_id = parseInt(l.building_type_id);
      l.bathroom_type_id = parseInt(l.bathroom_type_id);
      l.university_id = parseInt(l.university_id);
      return this.IdToSubletMap[l.id] = new A2Cribs.Sublet(l.id, l.university_id, l.building_type_id, l.name, l.street_address, l.city, l.state, l.date_begin, l.date_end, l.number_bedrooms, l.price_per_bedroom, l.description, l.number_bathrooms, l.bathroom_type_id, l.utility_cost, l.deposit_amount, l.additional_fees_description, l.additional_fees_amount, l.marker_id, l.flexible_dates, l.furnished_type_id);
    };

    /*
    	Add a list of subletIds to the MarkerIdToSubletIdsMap
    */

    Cache.CacheMarkerIdToSubletsList = function(sublets) {
      var sublet, _i, _len, _results;
      A2Cribs.Map.MarkerIdToSubletIdsMap[sublets[0].Sublet.marker_id] = [];
      _results = [];
      for (_i = 0, _len = sublets.length; _i < _len; _i++) {
        sublet = sublets[_i];
        if (sublet === void 0) continue;
        _results.push(this.MarkerIdToSubletIdsMap[sublet.Sublet.marker_id].push(parseInt(sublet.Sublets.sublet_id)));
      }
      return _results;
    };

    Cache.CacheUniversity = function(university) {
      var id;
      if (university === null) return;
      id = parseInt(university.id);
      return this.IdToUniversityMap[id] = new A2Cribs.University(university.city, university.domain, university.name, university.state);
    };

    Cache.CacheHoverData = function(marker_id, hoverData) {
      /*h = hoverData
      		@MarkerIdToHoverDataMap[marker_id] = new A2Cribs.HoverData(h.UnitType, @Beds, @Rent, @Duration)
      */
    };

    Cache.CacheHousemates = function(sublet_id, housemates) {
      var h, _i, _len, _results;
      if (housemates === null) return;
      sublet_id = parseInt(sublet_id);
      this.SubletIdToHousemateIdsMap[sublet_id] = [];
      _results = [];
      for (_i = 0, _len = housemates.length; _i < _len; _i++) {
        h = housemates[_i];
        h.id = parseInt(h.id);
        this.IdToHousematesMap[h.id] = new A2Cribs.Housemate(sublet_id, h.enrolled, h.major, h.seeking, h.type);
        _results.push(this.SubletIdToHousemateIdsMap[sublet_id].push(h.id));
      }
      return _results;
    };

    Cache.CacheMarker = function(id, marker) {
      var m;
      m = marker;
      return this.IdToMarkerMap[id] = new A2Cribs.Marker(id, m.address, m.alternate_name, m.unit_type, m.latitude, m.longitude);
    };

    /*
    	Add sublet data to cache
    */

    Cache.CacheMarkerData = function(markerDataList) {
      var markerData, marker_id, sublet, _i, _len, _results;
      if (markerDataList[0] !== void 0 && markerDataList[0].Sublet !== void 0) {
        marker_id = parseInt(markerDataList[0].Sublet.marker_id);
        this.MarkerIdToSubletIdsMap[marker_id] = [];
      }
      _results = [];
      for (_i = 0, _len = markerDataList.length; _i < _len; _i++) {
        markerData = markerDataList[_i];
        sublet = markerData.Sublet;
        A2Cribs.Cache.CacheSublet(sublet);
        A2Cribs.Cache.CacheHousemates(sublet.id, markerData.Housemate);
        _results.push(A2Cribs.Cache.CacheUniversity(markerData.University));
      }
      return _results;
    };

    return Cache;

  })();

}).call(this);
