(function() {

  A2Cribs.Map = (function() {

    function Map() {}

    /*
    	Called when a marker is clicked
    */

    Map.MarkerClicked = function(event) {
      return A2Cribs.Cache.IdToMarkerMap[this.id].LoadMarkerData();
    };

    Map.MarkerMouseIn = function(event) {
      return A2Cribs.Map.HoverBubble.Open(A2Cribs.Cache.IdToMarkerMap[this.id]);
    };

    Map.MarkerMouseOut = function(event) {
      return A2Cribs.Map.HoverBubble.Close();
    };

    /*
    	Add a marker to the map
    */

    Map.AddMarker = function(m) {
      var id;
      id = parseInt(m.marker_id, 10);
      A2Cribs.Cache.CacheMarker(id, m);
      this.GMarkerClusterer.addMarker(A2Cribs.Cache.IdToMarkerMap[id].GMarker);
      google.maps.event.addListener(A2Cribs.Cache.IdToMarkerMap[id].GMarker, 'click', this.MarkerClicked);
      google.maps.event.addListener(A2Cribs.Cache.IdToMarkerMap[id].GMarker, 'mouseover', this.MarkerMouseIn);
      google.maps.event.addListener(A2Cribs.Cache.IdToMarkerMap[id].GMarker, 'mouseout', this.MarkerMouseOut);
      return A2Cribs.Cache.AddressToMarkerIdMap[m.address] = parseInt(m.marker_id);
    };

    /*
    	Add all markers in markerList to map
    */

    Map.InitializeMarkers = function(markerList) {
      var decodedMarkerList, marker, _i, _len;
      decodedMarkerList = JSON.parse(markerList);
      for (_i = 0, _len = decodedMarkerList.length; _i < _len; _i++) {
        marker = decodedMarkerList[_i];
        this.AddMarker(marker.Marker);
      }
      if (A2Cribs.marker_id_to_open >= 0) {
        A2Cribs.Cache.IdToMarkerMap[A2Cribs.marker_id_to_open].GMarker.setIcon("/img/dots/clicked_dot.png");
      }
      return A2Cribs.Map.LoadHoverData();
    };

    /*
    	Load all markers from Markers table
    */

    Map.LoadMarkers = function() {
      if (A2Cribs.Map.CurentSchoolId === void 0) return;
      return $.ajax({
        url: myBaseUrl + "Map/LoadMarkers/" + A2Cribs.Map.CurentSchoolId,
        type: "GET",
        context: this,
        success: this.InitializeMarkers
      });
      /*defaultBounds = new google.maps.LatLngBounds(new google.maps.LatLng(42.23472,-83.846283), new google.maps.LatLng(42.33322,-83.627243))
      		input = $("#addressSearchBar")[0]
      		options = 
      			bounds: defaultBounds
      		@AutoComplete = new google.maps.places.Autocomplete(input, options)
      		@AutoComplete.setBounds(defaultBounds)
      */
    };

    /*
    	Used to only show markers that are within a certain bounds based on the user's current viewport.
    	https://developers.google.com/maps/articles/toomanymarkers#viewportmarkermanagement
    */

    Map.ShowMarkers = function() {
      var bounds;
      return bounds = A2Cribs.Map.GMap.getBounds();
    };

    Map.InitBoundaries = function() {
      return this.Bounds = {
        LEFT: 0,
        RIGHT: window.innerWidth,
        BOTTOM: window.innerHeight,
        TOP: 0,
        CONTROL_BOX_LEFT: 95
      };
    };

    Map.Init = function(school_id, latitude, longitude, city, state, school_name) {
      var imageStyles, mcOptions, style, zoom;
      this.CurentSchoolId = school_id;
      A2Cribs.FilterManager.CurrentCity = city;
      A2Cribs.FilterManager.CurrentState = state;
      A2Cribs.FilterManager.CurrentSchool = school_name;
      zoom = 15;
      if (A2Cribs.marker_id_to_open >= 0) {
        this.MapCenter = new google.maps.LatLng(A2Cribs.loaded_sublet_data.Marker.latitude, A2Cribs.loaded_sublet_data.Marker.longitude, zoom = 18);
      } else {
        this.MapCenter = new google.maps.LatLng(latitude, longitude);
      }
      style = [];
      this.MapOptions = {
        zoom: zoom,
        center: A2Cribs.Map.MapCenter,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: style,
        panControl: false,
        streetViewControl: false,
        mapTypeControl: false
      };
      A2Cribs.Map.GMap = new google.maps.Map(document.getElementById('map_canvas'), A2Cribs.Map.MapOptions);
      google.maps.event.addListener(A2Cribs.Map.GMap, 'idle', A2Cribs.Map.ShowMarkers);
      /*imageStyles = [
      			{
      				"url": "/img/dots/group_dot.png",
      			}
      		]
      */
      imageStyles = [
        {
          height: 48,
          url: '/img/dots/group_dot.png',
          width: 48,
          textColor: '#ffffff',
          textSize: 13
        }
      ];
      mcOptions = {
        gridSize: 60,
        maxZoom: 15,
        styles: imageStyles
      };
      this.GMarkerClusterer = new MarkerClusterer(A2Cribs.Map.GMap, [], mcOptions);
      this.GMarkerClusterer.ignoreHidden_ = true;
      this.LoadTypeTables();
      this.ClickBubble = new A2Cribs.ClickBubble(this.GMap);
      this.HoverBubble = new A2Cribs.HoverBubble(this.GMap);
      this.ListingPopup = new A2Cribs.ListingPopup();
      if (A2Cribs.marker_id_to_open >= 0) {
        A2Cribs.Cache.CacheMarker(A2Cribs.marker_id_to_open, A2Cribs.loaded_sublet_data.Marker);
        A2Cribs.Cache.CacheMarkerData([A2Cribs.loaded_sublet_data]);
        this.ListingPopup.Open(A2Cribs.loaded_sublet_data.Sublet.id);
      } else if (A2Cribs.marker_id_to_open === -2) {
        alertify.alert("Sorry. This listing no longer exists!");
      }
      A2Cribs.Map.InitBoundaries();
      A2Cribs.MarkerTooltip.Init();
      A2Cribs.FavoritesManager.LoadFavorites();
      return A2Cribs.FilterManager.InitAddressSearch();
    };

    Map.LoadTypeTables = function() {
      return $.ajax({
        url: myBaseUrl + "Map/LoadTypeTables",
        type: "POST",
        success: this.LoadTypeTablesCallback
      });
    };

    Map.LoadHoverData = function() {
      return $.ajax({
        url: myBaseUrl + "Map/LoadHoverData",
        type: "POST",
        success: this.LoadHoverDataCallback
      });
    };

    Map.LoadHoverDataCallback = function(response) {
      var hdList;
      hdList = JSON.parse(response);
      return A2Cribs.Cache.CacheHoverData(hdList);
    };

    Map.LoadTypeTablesCallback = function(types) {
      var bathrooms, buildings, genders, student_types, type, _i, _j, _k, _l, _len, _len2, _len3, _len4;
      types = JSON.parse(types);
      buildings = types[0];
      bathrooms = types[1];
      genders = types[2];
      student_types = types[3];
      for (_i = 0, _len = buildings.length; _i < _len; _i++) {
        type = buildings[_i];
        A2Cribs.Cache.BuildingIdToNameMap[parseInt(type.BuildingType.id)] = type.BuildingType.name;
      }
      for (_j = 0, _len2 = bathrooms.length; _j < _len2; _j++) {
        type = bathrooms[_j];
        A2Cribs.Cache.BathroomIdToNameMap[parseInt(type.BathroomType.id)] = type.BathroomType.name;
      }
      for (_k = 0, _len3 = genders.length; _k < _len3; _k++) {
        type = genders[_k];
        A2Cribs.Cache.GenderIdToNameMap[parseInt(type.GenderType.id)] = type.GenderType.name;
      }
      for (_l = 0, _len4 = student_types.length; _l < _len4; _l++) {
        type = student_types[_l];
        A2Cribs.Cache.StudentTypeIdToNameMap[parseInt(type.StudentType.id)] = type.StudentType.name;
      }
      return A2Cribs.Map.LoadMarkers();
    };

    /*
    	EVAN:
    		marker_id is the id of the marker to open
    		sublet_data is an object containing all the data needed to populate a tooltip
    */

    Map.OpenMarker = function(marker_id, sublet_data) {
      if (marker_id === -1) {
        alert("This listing either has been removed or is invalid.");
        return;
      }
      if (marker_id === -2) return;
      return alert(marker_id);
    };

    return Map;

  })();

}).call(this);
