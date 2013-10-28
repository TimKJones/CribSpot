(function() {

  A2Cribs.Map = (function() {

    function Map() {}

    Map.CLUSTER_SIZE = 2;

    /*
    	Add all markers in markerList to map
    */

    Map.InitializeMarkers = function(markerList) {
      var marker, marker_object, _i, _len, _results;
      if (markerList != null) {
        markerList = JSON.parse(markerList);
        _results = [];
        for (_i = 0, _len = markerList.length; _i < _len; _i++) {
          marker_object = markerList[_i];
          marker = new A2Cribs.Marker(marker_object.Marker);
          marker.Init();
          A2Cribs.UserCache.Set(marker);
          _results.push(Map.GMarkerClusterer.addMarker(marker.GMarker));
        }
        return _results;
      }
    };

    /*
    	Load all markers from Markers table
    */

    Map.LoadMarkers = function() {
      if (!this.MarkerDeferred) this.MarkerDeferred = new $.Deferred();
      if (A2Cribs.Map.CurentSchoolId === void 0) {
        this.MarkerDeferred.resolve(null);
        return;
      }
      $.ajax({
        url: myBaseUrl + "Map/LoadMarkers/" + A2Cribs.Map.CurentSchoolId + "/" + 0,
        type: "GET",
        context: this,
        success: function(response) {
          return this.MarkerDeferred.resolve(response, this);
        },
        error: function() {
          return this.MarkerDeferred.resolve(null);
        }
      });
      return this.MarkerDeferred.promise();
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

    Map.Init = function(school_id, latitude, longitude, city, state, school_name, active_listing_type) {
      var imageStyles, mcOptions, zoom,
        _this = this;
      this.CurentSchoolId = school_id;
      mixpanel.register({
        'preferred_university': school_id
      });
      A2Cribs.FilterManager.CurrentCity = city;
      A2Cribs.FilterManager.CurrentState = state;
      A2Cribs.FilterManager.CurrentSchool = school_name;
      this.ACTIVE_LISTING_TYPE = active_listing_type;
      zoom = 14;
      this.MapCenter = new google.maps.LatLng(latitude, longitude);
      this.MapOptions = {
        zoom: zoom,
        center: A2Cribs.Map.MapCenter,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: this.style,
        panControl: false,
        streetViewControl: false,
        mapTypeControl: false,
        zoomControlOptions: {
          style: google.maps.ZoomControlStyle.SMALL,
          position: google.maps.ControlPosition.TOP_RIGHT
        }
      };
      A2Cribs.Map.GMap = new google.maps.Map(document.getElementById('map_canvas'), A2Cribs.Map.MapOptions);
      google.maps.event.addListener(A2Cribs.Map.GMap, 'idle', A2Cribs.Map.ShowMarkers);
      google.maps.event.addListener(A2Cribs.Map.GMap, 'center_changed', function() {
        return A2Cribs.ClickBubble.Close();
      });
      /*imageStyles = [
      			{
      				"url": "/img/dots/group_dot.png",
      			}
      		]
      */
      imageStyles = [
        {
          height: 39,
          url: '/img/dots/dot_group.png',
          width: 39,
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
      this.GMarkerClusterer.setIgnoreHidden(true);
      A2Cribs.ClickBubble.Init(this.GMap);
      A2Cribs.HoverBubble.Init(this.GMap);
      A2Cribs.Map.InitBoundaries();
      this.LoadAllMapData();
      return A2Cribs.FilterManager.InitAddressSearch();
    };

    Map.LoadBasicData = function() {
      var _this = this;
      if (!(this.BasicDataDeferred != null)) {
        this.BasicDataDeferred = new $.Deferred();
      }
      $.ajax({
        url: myBaseUrl + ("Map/GetBasicData/" + this.ACTIVE_LISTING_TYPE + "/" + this.CurentSchoolId),
        type: "POST",
        success: function(responses) {
          return _this.BasicDataDeferred.resolve(responses);
        },
        error: function() {
          _this.BasicDataDeferred.resolve(null);
          return _this.BasicDataCached.resolve();
        }
      });
      return this.BasicDataDeferred.promise();
    };

    Map.LoadBasicDataCallback = function(response) {
      var all_listings, all_markers, is_available, is_scheduled, key, listing, listings, lol, marker, value, _i, _j, _k, _l, _len, _len2, _len3, _len4, _results;
      if (response === null || response === void 0) return;
      listings = JSON.parse(response);
      for (_i = 0, _len = listings.length; _i < _len; _i++) {
        listing = listings[_i];
        for (key in listing) {
          value = listing[key];
          A2Cribs.UserCache.Set(new A2Cribs[key](value));
        }
      }
      Map.BasicDataCached.resolve();
      all_markers = A2Cribs.UserCache.Get("marker");
      for (_j = 0, _len2 = all_markers.length; _j < _len2; _j++) {
        marker = all_markers[_j];
        listings = A2Cribs.UserCache.GetAllAssociatedObjects("listing", "marker", marker.GetId());
        is_available = false;
        is_scheduled = false;
        for (_k = 0, _len3 = listings.length; _k < _len3; _k++) {
          listing = listings[_k];
          if (!(listing.available != null) && is_available !== true) {
            is_available = null;
          } else if ((listing.available != null) && listing.available === true) {
            is_available = true;
          }
          if (listing.scheduling === true) {
            is_scheduled = true;
            lol = "lol";
          }
        }
        marker.Init(is_available, is_scheduled);
        Map.GMarkerClusterer.addMarker(marker.GMarker);
      }
      all_listings = A2Cribs.UserCache.Get("listings");
      _results = [];
      for (_l = 0, _len4 = all_listings.length; _l < _len4; _l++) {
        listing = all_listings[_l];
        _results.push(listing.visible = true);
      }
      return _results;
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
      var basicData;
      $("#loader").show();
      basicData = this.LoadBasicData();
      this.BasicDataCached = new $.Deferred();
      A2Cribs.FeaturedListings.LoadFeaturedPMListings();
      basicData.done(this.LoadBasicDataCallback).always(function() {
        return $("#loader").hide();
      });
      return A2Cribs.FeaturedListings.InitializeSidebar(this.CurentSchoolId, this.ACTIVE_LISTING_TYPE, basicData, this.BasicDataCached);
    };

    Map.CenterMap = function(latitude, longitude) {
      if (!(this.GMap != null)) return;
      return this.GMap.setCenter(new google.maps.LatLng(latitude, longitude));
    };

    /*
    	Toggles visibility for the given listing_ids
    	When toggled on, only these listing_ids are visible.
    	When toggled off, all listings are visible
    */

    Map.ToggleListingVisibility = function(listing_ids, toggle_type) {
      var all_listings, all_markers, is_current_toggle, listing, listing_id, marker, _i, _j, _k, _l, _len, _len2, _len3, _len4, _len5, _m, _ref, _ref2;
      $(".favorite_button").removeClass("active");
      $(".featured_pm").removeClass("active");
      if ((_ref = A2Cribs.HoverBubble) != null) _ref.Close();
      if ((_ref2 = A2Cribs.ClickBubble) != null) _ref2.Close();
      all_markers = A2Cribs.UserCache.Get('marker');
      all_listings = A2Cribs.UserCache.Get('listing');
      is_current_toggle = this.CurrentToggle === toggle_type;
      if (!is_current_toggle) {
        for (_i = 0, _len = all_markers.length; _i < _len; _i++) {
          marker = all_markers[_i];
          marker.IsVisible(false);
        }
        for (_j = 0, _len2 = all_listings.length; _j < _len2; _j++) {
          listing = all_listings[_j];
          listing.IsVisible(false);
        }
        for (_k = 0, _len3 = listing_ids.length; _k < _len3; _k++) {
          listing_id = listing_ids[_k];
          listing = A2Cribs.UserCache.Get('listing', listing_id);
          if (listing != null) {
            marker = A2Cribs.UserCache.Get('marker', listing.marker_id);
            marker.IsVisible(true);
            listing.IsVisible(true);
          }
        }
        this.CurrentToggle = toggle_type;
      } else {
        for (_l = 0, _len4 = all_markers.length; _l < _len4; _l++) {
          marker = all_markers[_l];
          if (marker != null) marker.IsVisible(true);
        }
        for (_m = 0, _len5 = all_listings.length; _m < _len5; _m++) {
          listing = all_listings[_m];
          listing.IsVisible(true);
        }
        this.CurrentToggle = null;
      }
      this.Repaint();
      return is_current_toggle;
    };

    /*
    	Checks/Sets if the map is in clusters
    */

    Map.IsCluster = function(is_clustered) {
      if (is_clustered == null) is_clustered = null;
      if (typeof is_clustered === "boolean") {
        if (is_clustered === true) {
          this.GMarkerClusterer.setMinimumClusterSize(this.CLUSTER_SIZE);
        } else {
          this.GMarkerClusterer.setMinimumClusterSize(Number.MAX_VALUE);
        }
        this.Repaint();
      }
      return this.GMarkerClusterer.getMinimumClusterSize() === this.CLUSTER_SIZE;
    };

    /*
    	Repaints the map
    */

    Map.Repaint = function() {
      return this.GMarkerClusterer.repaint();
    };

    Map.style = [
      {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
          {
            "visibility": "on"
          }, {
            "color": "#ffffff"
          }
        ]
      }, {
        "featureType": "road.arterial",
        "elementType": "geometry.fill",
        "stylers": [
          {
            "color": "#ffffff"
          }
        ]
      }, {
        "elementType": "labels.text.fill",
        "stylers": [
          {
            "color": "#3b393a"
          }
        ]
      }, {
        "featureType": "poi.sports_complex",
        "elementType": "geometry",
        "stylers": [
          {
            "color": "#e9ddbc"
          }
        ]
      }, {
        "featureType": "road",
        "elementType": "labels.text.stroke",
        "stylers": [
          {
            "color": "#ffffff"
          }
        ]
      }, {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
          {
            "color": "#868080"
          }, {
            "lightness": 55
          }
        ]
      }, {
        "featureType": "road.local",
        "elementType": "geometry.stroke",
        "stylers": [
          {
            "color": "#808080"
          }, {
            "lightness": 53
          }
        ]
      }, {
        "featureType": "poi.place_of_worship",
        "elementType": "labels",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.attraction",
        "elementType": "labels",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "road.highway",
        "elementType": "labels.icon",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "road"
      }, {
        "featureType": "transit.station.airport",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.government",
        "elementType": "labels",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.business",
        "elementType": "labels",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.government",
        "elementType": "labels",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.medical",
        "elementType": "labels",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.park",
        "elementType": "labels.icon",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi",
        "elementType": "labels.icon",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.park",
        "elementType": "labels.text.fill",
        "stylers": [
          {
            "lightness": 23
          }, {
            "color": "#83b243"
          }, {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.park",
        "elementType": "labels.text.stroke",
        "stylers": [
          {
            "color": "#f4f6f1"
          }, {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.school",
        "elementType": "labels.text",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "water",
        "elementType": "labels",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "road.highway",
        "elementType": "labels.icon",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.medical",
        "stylers": [
          {
            "color": "#ce979e"
          }, {
            "lightness": 26
          }
        ]
      }, {
        "featureType": "road.arterial",
        "elementType": "labels.icon",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "transit.station.rail",
        "elementType": "labels.icon",
        "stylers": [
          {
            "lightness": 39
          }
        ]
      }, {
        "featureType": "poi.park",
        "elementType": "geometry.fill",
        "stylers": [
          {
            "color": "#d6e0c6"
          }
        ]
      }, {
        "featureType": "water",
        "stylers": [
          {
            "color": "#c2d6ec"
          }
        ]
      }, {
        "featureType": "landscape.man_made",
        "stylers": [
          {
            "color": "#efece2"
          }
        ]
      }, {
        "featureType": "poi.medical",
        "stylers": [
          {
            "color": "#edcece"
          }
        ]
      }, {
        "featureType": "road.arterial",
        "elementType": "labels.icon",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "road.local",
        "elementType": "labels.text.fill",
        "stylers": [
          {
            "lightness": 16
          }
        ]
      }, {
        "featureType": "road.arterial",
        "stylers": [
          {
            "lightness": 15
          }
        ]
      }, {
        "featureType": "landscape.man_made",
        "elementType": "geometry.stroke",
        "stylers": [
          {
            "visibility": "on"
          }, {
            "lightness": 78
          }, {
            "color": "#b8b7b8"
          }
        ]
      }, {
        "featureType": "poi.business",
        "elementType": "geometry.fill",
        "stylers": [
          {
            "visibility": "on"
          }, {
            "lightness": 25
          }, {
            "saturation": -17
          }
        ]
      }
    ];

    return Map;

  }).call(this);

}).call(this);
