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

    Map.InitializeMarkers = function(markerList, that) {
      var marker, _i, _len;
      if (markerList === null || markerList === void 0) return;
      markerList = JSON.parse(markerList);
      for (_i = 0, _len = markerList.length; _i < _len; _i++) {
        marker = markerList[_i];
        that.AddMarker(marker.Marker);
      }
      if (A2Cribs.marker_id_to_open >= 0) {
        return A2Cribs.Cache.IdToMarkerMap[A2Cribs.marker_id_to_open].GMarker.setIcon("/img/dots/clicked_dot.png");
      }
    };

    /*
    	Load all markers from Markers table
    */

    Map.LoadMarkers = function() {
      var deferred;
      deferred = new $.Deferred;
      if (A2Cribs.Map.CurentSchoolId === void 0) {
        deferred.resolve(null);
        return;
      }
      $.ajax({
        url: myBaseUrl + "Map/LoadMarkers/" + A2Cribs.Map.CurentSchoolId + "/" + 0,
        type: "GET",
        context: this,
        success: function(response) {
          return deferred.resolve(response, this);
        },
        error: function() {
          return deferred.resolve(null);
        }
      });
      return deferred.promise();
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

    Map.LoadHoverData = function() {
      var deferred;
      deferred = new $.Deferred;
      $.ajax({
        url: myBaseUrl + "Map/LoadHoverData/" + 0,
        type: "POST",
        success: function(responses) {
          return deferred.resolve(responses);
        },
        error: function() {
          return deferred.resolve(null);
        }
      });
      return deferred.promise();
    };

    Map.LoadHoverDataCallback = function(response) {
      var hdList;
      if (response === null || response === void 0) return;
      hdList = JSON.parse(response);
      return A2Cribs.UserCache.Set(new A2Cribs.HoverData(hdList));
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

    /*
    	Load markers and hover data.
    	Use JQuery Deferred object to load all data asynchronously
    */

    Map.LoadAllMapData = function() {
      var hoverDataPromise, markersPromise;
      markersPromise = this.LoadMarkers();
      hoverDataPromise = this.LoadHoverData();
      $.when(markersPromise).then(this.InitializeMarkers);
      $.when(hoverDataPromise).then(this.LoadHoverDataCallback);
      return $.when(markersPromise, hoverDataPromise).done(function() {
        return alert('everthing has been loaded');
      });
    };

    return Map;

  })();

}).call(this);
