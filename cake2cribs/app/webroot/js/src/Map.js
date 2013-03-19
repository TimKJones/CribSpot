(function() {

  A2Cribs.Map = (function() {

    function Map() {}

    Map.CurentSchoolId = 171;

    /*
    	Called when a marker is clicked
    */

    Map.MarkerClicked = function(event) {
      return A2Cribs.Map.IdToMarkerMap[this.id].LoadMarkerData();
    };

    /*
    	Add list of listings to cache
    */

    Map.CacheListings = function(listings) {
      var l, listing, _i, _len, _results;
      A2Cribs.Map.MarkerIdToListingIdsMap[listings[0].Listing.marker_id] = [];
      _results = [];
      for (_i = 0, _len = listings.length; _i < _len; _i++) {
        listing = listings[_i];
        if (listing === void 0) continue;
        l = listing.Listing;
        l.listing_id = parseInt(l.listing_id);
        _results.push(A2Cribs.Map.IdToListingMap[l.listing_id] = new A2Cribs.Listing(l.listing_id, l.marker_id, l.available, l.lease_range, l.unit_type, l.unit_description, l.beds, l.baths, l.rent, l.electric, l.water, l.heat, l.air, l.parking, l.furnished, l.url, l.realtor_id));
      }
      return _results;
    };

    /*
    	Add a realtor to the cache
    */

    Map.CacheRealtor = function(realtor) {
      realtor.realtor_id = parseInt(realtor.realtor_id);
      return A2Cribs.Map.IdToRealtorMap[parseInt(realtor.realtor_id)] = new A2Cribs.Realtor(realtor.realtor_id, realtor.company, realtor.email);
    };

    /*
    	Add a list of listingIds to the MarkerIdToListingIds map
    */

    Map.CacheMarkerIdToListingsList = function(listings) {
      var listing, _i, _len, _results;
      A2Cribs.Map.MarkerIdToListingIdsMap[listings[0].Listing.marker_id] = [];
      _results = [];
      for (_i = 0, _len = listings.length; _i < _len; _i++) {
        listing = listings[_i];
        if (listing === void 0) continue;
        _results.push(A2Cribs.Map.MarkerIdToListingIdsMap[listing.Listing.marker_id].push(parseInt(listing.Listing.listing_id)));
      }
      return _results;
    };

    /*
    	Add a marker to the map
    */

    Map.AddMarker = function(m) {
      var id;
      id = parseInt(m["marker_id"], 10);
      this.IdToMarkerMap[id] = new A2Cribs.Marker(id, m.address, m.alternate_name, m.unit_type, m.latitude, m.longitude);
      this.GMarkerClusterer.addMarker(this.IdToMarkerMap[id].GMarker);
      google.maps.event.addListener(this.IdToMarkerMap[id].GMarker, 'click', this.MarkerClicked);
      return A2Cribs.Map.AddressToMarkerIdMap[m['address']] = m['marker_id'];
    };

    /*
    	Add all markers in markerList to map
    */

    Map.InitializeMarkers = function(markerList) {
      var decodedMarkerList, marker, _i, _len, _results;
      decodedMarkerList = JSON.parse(markerList);
      _results = [];
      for (_i = 0, _len = decodedMarkerList.length; _i < _len; _i++) {
        marker = decodedMarkerList[_i];
        _results.push(this.AddMarker(marker["Marker"]));
      }
      return _results;
    };

    /*
    	Load all markers from Markers table
    */

    Map.LoadMarkers = function() {
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
        FILTER_BOX_LEFT: A2Cribs.UtilityFunctions.getPosition($("#filterBoxBackground")[0]).x,
        FILTER_BOX_BOTTOM: A2Cribs.UtilityFunctions.getPosition($("#filterBoxBackground")[0]).y + $("#filterBoxBackground").height(),
        CONTROL_BOX_LEFT: 95
      };
    };

    Map.Init = function() {
      var mcOptions, style;
      this.IdToListingMap = [];
      this.IdToRealtorMap = [];
      this.MarkerIdToListingIdsMap = [];
      this.IdToMarkerMap = [];
      this.AddressToMarkerIdMap = [];
      this.AnnArborCenter = new google.maps.LatLng(42.2808256, -83.7430378);
      style = [
        {
          "featureType": "landscape",
          "stylers": [
            {
              "hue": "#005eff"
            }
          ]
        }, {
          "featureType": "road",
          "stylers": [
            {
              "hue": "#00ff19"
            }
          ]
        }, {
          "featureType": "water",
          "stylers": [
            {
              "saturation": 99
            }
          ]
        }, {
          "featureType": "poi",
          "stylers": [
            {
              "hue": "#0044ff"
            }, {
              "lightness": 32
            }
          ]
        }
      ];
      this.MapOptions = {
        zoom: 15,
        center: A2Cribs.Map.AnnArborCenter,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: style,
        panControl: false,
        streetViewControl: false,
        mapTypeControl: false
      };
      A2Cribs.Map.GMap = new google.maps.Map(document.getElementById('map_canvas'), A2Cribs.Map.MapOptions);
      google.maps.event.addListener(A2Cribs.Map.GMap, 'idle', A2Cribs.Map.ShowMarkers);
      mcOptions = {
        gridSize: 60,
        maxZoom: 15
      };
      this.GMarkerClusterer = new MarkerClusterer(A2Cribs.Map.GMap, [], mcOptions);
      this.GMarkerClusterer.ignoreHidden_ = true;
      this.LoadMarkers();
      this.MarkerTooltip = new A2Cribs.MarkerTooltip(this.GMap);
      A2Cribs.FilterManager.InitAddressSearch();
      A2Cribs.Map.InitBoundaries();
      return A2Cribs.MarkerTooltip.Init();
    };

    Map.UpdateMarkersCache = function() {
      return $.ajax({
        url: myBaseUrl + "Markers/UpdateCache"
      });
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
      return alert(marker_id);
    };

    return Map;

  })();

}).call(this);
