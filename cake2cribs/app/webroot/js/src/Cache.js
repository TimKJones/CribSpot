(function() {
  var __indexOf = Array.prototype.indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  A2Cribs.Cache = (function() {

    function Cache() {}

    Cache.IdToSubletMap = [];

    Cache.IdToMarkerMap = [];

    Cache.IdToUniversityMap = [];

    Cache.IdToHousematesMap = [];

    Cache.SubletIdToHousemateIdsMap = [];

    Cache.SubletIdToOwnerMap = [];

    Cache.SubletIdToImagesMap = [];

    Cache.MarkerIdToHoverDataMap = [];

    Cache.MarkerIdToSubletIdsMap = [];

    Cache.IdToMarkerMap = [];

    Cache.AddressToMarkerIdMap = [];

    Cache.BuildingIdToNameMap = [];

    Cache.BathroomIdToNameMap = [];

    Cache.GenderIdToNameMap = [];

    Cache.StudentTypeIdToNameMap = [];

    Cache.FavoritesSubletIdsList = [];

    Cache.FavoritesMarkerIdsList = [];

    Cache.IdToRentalMap = [];

    Cache.IdToParkingMap = [];

    Cache.ListingIdToUserMap = [];

    Cache.SubletEditInProgress = null;

    /*
    	Add list of sublets to cache
    */

    Cache.CacheSublet = function(sublet) {
      var bathroom, building, l;
      l = sublet;
      l.id = parseInt(l.id);
      l.marker_id = parseInt(l.marker_id);
      this.MarkerIdToSubletIdsMap[parseInt(sublet.marker_id)].push(l.id);
      l.number_bedrooms = parseInt(l.number_bedrooms);
      l.price_per_bedroom = parseInt(l.price_per_bedroom);
      l.number_bedrooms = parseInt(l.number_bedrooms);
      l.utility_cost = parseInt(l.utility_cost);
      l.deposit_amount = parseInt(l.deposit_amount);
      l.additional_fees_amount = parseInt(l.additional_fees_amount);
      l.marker_id = parseInt(l.marker_id);
      l.furnished_type_id = parseInt(l.furnished_type_id);
      building = this.IdToMarkerMap[l.marker_id].UnitType;
      l.bathroom_type_id = parseInt(l.bathroom_type_id);
      bathroom = this.BathroomIdToNameMap[l.bathroom_type_id];
      l.university_id = parseInt(l.university_id);
      return this.IdToSubletMap[l.id] = new A2Cribs.Sublet(l.id, l.university_id, building, l.name, l.street_address, l.city, l.state, l.date_begin, l.date_end, l.number_bedrooms, l.price_per_bedroom, l.short_description, bathroom, l.utility_cost, l.deposit_amount, l.additional_fees_description, l.additional_fees_amount, l.marker_id, l.flexible_dates, l.furnished_type_id, l.created, l.ac, l.parking);
    };

    /*
    	Add a list of subletIds to the MarkerIdToSubletIdsMap
    */

    Cache.CacheMarkerIdToSubletsList = function(sublets) {
      var sublet, _i, _len, _results;
      A2Cribs.Map.MarkerIdToSubletIdsMap[parseInt(sublets[0].Sublet.marker_id)] = [];
      _results = [];
      for (_i = 0, _len = sublets.length; _i < _len; _i++) {
        sublet = sublets[_i];
        if (sublet === void 0) continue;
        _results.push(this.MarkerIdToSubletIdsMap[parseInt(sublet.Sublet.marker_id)].push(parseInt(sublet.Sublets.sublet_id)));
      }
      return _results;
    };

    Cache.CacheUniversity = function(university) {
      var id;
      if (university === null) return;
      id = parseInt(university.id);
      return this.IdToUniversityMap[id] = new A2Cribs.University(university.city, university.domain, university.name, university.state);
    };

    Cache.CacheHoverData = function(hoverDataList) {
      /*
      		TODO: find min and max dates
      */
      var beds, building_type_id, hd, hdList, markerIdToHd, marker_id, maxBeds, maxDate, maxRent, minBeds, minDate, minRent, numListings, price, sublet, unitType, _i, _j, _len, _len2;
      markerIdToHd = [];
      for (_i = 0, _len = hoverDataList.length; _i < _len; _i++) {
        hd = hoverDataList[_i];
        marker_id = null;
        if (hd !== null) {
          marker_id = parseInt(hd.Sublet.marker_id);
          if (this.IdToMarkerMap[marker_id] === void 0) {
            continue;
          } else {
            if (markerIdToHd[marker_id] === void 0) markerIdToHd[marker_id] = [];
            markerIdToHd[marker_id].push(hd);
          }
        } else {
          continue;
        }
      }
      for (marker_id in markerIdToHd) {
        hdList = markerIdToHd[marker_id];
        numListings = hdList.length;
        sublet = hdList[0].Sublet;
        if (sublet === void 0 || sublet === null) return;
        unitType = this.IdToMarkerMap[marker_id].UnitType;
        minBeds = parseInt(sublet.number_bedrooms);
        maxBeds = parseInt(sublet.number_bedrooms);
        minRent = parseInt(sublet.price_per_bedroom);
        maxRent = parseInt(sublet.price_per_bedroom);
        minDate = sublet.date_begin;
        maxDate = sublet.date_end;
        for (_j = 0, _len2 = hdList.length; _j < _len2; _j++) {
          hd = hdList[_j];
          sublet = hd.Sublet;
          building_type_id = parseInt(sublet.building_type_id);
          beds = parseInt(sublet.number_bedrooms);
          price = parseInt(sublet.price_per_bedroom);
          if (beds < minBeds) minBeds = beds;
          if (beds > maxBeds) maxBeds = beds;
          if (price < minRent) minRent = price;
          if (price > maxRent) maxRent = price;
        }
        hd = new A2Cribs.HoverData(numListings, unitType, minBeds, maxBeds, minRent, maxRent, minDate, maxDate);
        this.MarkerIdToHoverDataMap[marker_id] = hd;
      }
    };

    Cache.CacheHousemates = function(housemates) {
      var gender, grad_status, id, quantity, sublet_id;
      if (!(housemates != null)) return;
      sublet_id = null;
      if (housemates.sublet_id != null) {
        sublet_id = parseInt(housemates.sublet_id);
      } else {
        return;
      }
      this.SubletIdToHousemateIdsMap[sublet_id] = [];
      id = parseInt(housemates.id);
      grad_status = this.StudentTypeIdToNameMap[parseInt(housemates.student_type_id)];
      gender = this.GenderIdToNameMap[parseInt(housemates.gender_type_id)];
      sublet_id = parseInt(housemates.sublet_id);
      quantity = parseInt(housemates.quantity);
      this.IdToHousematesMap[id] = new A2Cribs.Housemate(sublet_id, housemates.enrolled, housemates.major, housemates.seeking, grad_status, gender, quantity);
      return this.SubletIdToHousemateIdsMap[sublet_id].push(id);
    };

    Cache.CacheImages = function(imageList) {
      var caption, first_image, image, is_primary, path, sublet_id, _i, _len, _results;
      if (imageList === void 0 || imageList === null || imageList[0] === void 0) {
        return;
      }
      first_image = imageList[0];
      if (first_image === void 0 || first_image.sublet_id === void 0) return;
      sublet_id = parseInt(first_image.sublet_id);
      A2Cribs.Cache.SubletIdToImagesMap[sublet_id] = [];
      _results = [];
      for (_i = 0, _len = imageList.length; _i < _len; _i++) {
        image = imageList[_i];
        sublet_id = parseInt(image.sublet_id);
        path = "/" + image.image_path;
        is_primary = image.is_primary;
        caption = image.caption;
        _results.push(A2Cribs.Cache.SubletIdToImagesMap[sublet_id].push(new A2Cribs.Image(sublet_id, path, is_primary, caption)));
      }
      return _results;
    };

    Cache.CacheMarker = function(id, marker) {
      var m, unitType;
      m = marker;
      unitType = this.BuildingIdToNameMap[parseInt(m.building_type_id)];
      return this.IdToMarkerMap[id] = new A2Cribs.Marker(parseInt(id), m.street_address, m.alternate_name, unitType, m.latitude, m.longitude, m.city, m.state);
    };

    Cache.CacheSubletOwner = function(sublet_id, user) {
      var owner;
      owner = new A2Cribs.SubletOwner(user);
      return this.SubletIdToOwnerMap[sublet_id] = owner;
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
        A2Cribs.Cache.CacheHousemates(markerData.Housemate);
        A2Cribs.Cache.CacheSubletOwner(parseInt(sublet.id), markerData.User);
        _results.push(A2Cribs.Cache.CacheImages(markerData.Image));
      }
      return _results;
    };

    Cache.CacheSubletAddStep1 = function(data) {
      return A2Cribs.Cache.Step1Data = data;
    };

    Cache.CacheSubletAddStep2 = function(data) {
      return A2Cribs.Cache.Step2Data = data;
    };

    Cache.CacheSubletAddStep3 = function(data) {
      return A2Cribs.Cache.Step3Data = data;
    };

    /*
    	Adds new rental object to IdToRentalMap
    */

    Cache.AddRental = function(rental) {
      return this.IdToRentalMap[parseInt(rental.rental_id)] = rental;
    };

    /*
    	Creates a new Rental object from rental
    	Adds new rental object to IdToRentalMap
    */

    /*
    	Adds new parking object to IdToParkingMap
    */

    Cache.AddParking = function(parking) {
      return this.IdToParkingMap[parseInt(parking.parking_id)] = parking;
    };

    /*
    	Adds new user object to RentalIdToUserMap
    	IMPORTANT: only contains public, non-sensitive user data
    */

    Cache.AddUser = function(listing_id, user) {
      return this.ListingIdToUserMap[listing_id] = user;
    };

    /*
    	Returns listing object specified by listing_id
    */

    Cache.GetListing = function(listing_id) {
      var listing;
      if (__indexOf.call(this.IdToRentalMap, listing_id) >= 0) {
        return this.IdToRentalMap[listing_id];
      }
      listing = null;
      $.ajax({
        url: myBaseUrl + "Listings/GetListing/" + listing_id,
        type: "GET",
        context: this,
        async: false,
        success: function(response) {
          listing = JSON.parse(response);
          if (listing[0] !== void 0) {
            if (listing[0].Rental !== void 0) {
              this.AddRental(listing[0].Rental);
            } else if (listing[0].Parking !== void 0) {
              this.AddParking(listing[0].Parking);
            }
            return this.AddUser(parseInt(listing[0].Listing.listing_id), listing[0].User);
          }
        }
      });
      if (listing !== null) {
        return listing[0];
      } else {
        return null;
      }
    };

    /*
    	- returns the listing objectfor the given id
    	- if the listing is not in the cache
    		- fetches listing from database
    		- if listing_type is rental, add to IdToRentalMap
    		- if listing_type is parking, add to IdToParkingMap
    		- if listing_type is sublet, add to IdToSubletMap
    		- adds user to cache
    	- returns new listing/user object
    */

    Cache.GetListingsByUser = function(user_id) {};

    /*
    	- Loads all listings into cache with given user_id
    	- Loads PUBLIC user data for user into cache
    	- Returns array of listings for that PM
    */

    return Cache;

  })();

}).call(this);
