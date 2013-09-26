(function() {

  A2Cribs.ImportManager = (function() {

    function ImportManager() {}

    ImportManager.Indices = {
      marker_street_address: 0,
      city: 1,
      state: 2,
      zip: 3,
      latitude: 4,
      longitude: 5,
      unit_style_options: 6,
      unit_style_description: 7,
      min_rent: 8,
      max_rent: 9,
      beds: 10,
      baths: 11,
      start_date: 12,
      end_date: 13,
      alternate_start_date: 14,
      electric: 16,
      water: 17,
      gas: 18,
      trash: 19,
      cable: 20,
      internet: 21,
      utility_total_flat_rate: 22,
      square_feet: 23,
      air: 24,
      pets: 25,
      street_parking: 26,
      private_parking: 27,
      parking_type: 28,
      parking_cost: 29,
      furnished_type: 30,
      building_type_id: 31,
      alternate_name: 32,
      company_name: 33,
      phone: 34,
      email: 35,
      website: 36,
      tv: 37,
      balcony: 38,
      fridge: 39,
      storage: 40,
      pool: 41,
      hot_tub: 42,
      fitness_center: 43,
      game_room: 44,
      front_desk: 45,
      security_system: 46,
      tanning_beds: 47,
      study_lounge: 48,
      patio_deck: 49,
      yard_space: 50,
      elevator: 51,
      deposit: 52,
      admin_amount: 53,
      furniture_amount: 55,
      pets_amount: 56,
      amenity_amount: 57,
      upper_floor_amount: 58,
      extra_occupant_amount: 59,
      year_built: 61,
      min_occupancy: 62,
      max_occupancy: 62,
      unit_count: 63,
      smoking: 64,
      laundry: 65,
      user_street_address: 66,
      description: 67
    };

    ImportManager.GetListingsFromCSV = function(filename) {
      var url;
      if (filename == null) filename = null;
      url = myBaseUrl + "Import/GetListings/";
      if (filename !== null) url += "/" + filename;
      return $.ajax({
        url: url,
        type: "GET",
        context: this,
        success: function(response) {
          return this.ProcessAndSubmitListings(response);
        },
        error: function(response) {
          return console.log(response);
        }
      });
    };

    ImportManager.ProcessAndSubmitListings = function(listings) {
      var l, listing, parking_cost, parking_cost_type, parking_type, private_parking, processedListings, _i, _len, _ref, _results;
      console.log(JSON.parse(listings));
      listings = JSON.parse(listings);
      processedListings = [];
      _ref = listings[0];
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        l = _ref[_i];
        listing = {};
        listing['Marker'] = {};
        listing['Listing'] = {
          listing_type: 0,
          visible: 1
        };
        listing['Rental'] = {};
        listing['User'] = {};
        /*
        			The order of the fields is known from the excel template.
        			Go through each field, placing them in their correct container (listing, rental, or user)
        			Do any processing we can on each field
        */
        listing['Marker']['street_address'] = l[this.Indices['marker_street_address']];
        listing['Marker']['city'] = l[this.Indices['city']];
        listing['Marker']['state'] = l[this.Indices['state']];
        listing['Marker']['zip'] = l[this.Indices['zip']];
        listing['Marker']['latitude'] = l[this.Indices['latitude']];
        listing['Marker']['longitude'] = l[this.Indices['longitude']];
        listing['Rental']['unit_style_options'] = l[this.Indices['unit_style_options']];
        listing['Rental']['unit_style_description'] = l[this.Indices['unit_style_description']];
        listing['Rental']['rent'] = this.GetRent(l[this.Indices['min_rent']], l[this.Indices['max_rent']]);
        listing['Rental']['beds'] = l[this.Indices['beds']];
        listing['Rental']['baths'] = l[this.Indices['baths']];
        /*
        			#??? Need to format dates???
        */
        listing['Rental']['start_date'] = l[this.Indices['start_date']];
        listing['Rental']['end_date'] = l[this.Indices['end_date']];
        listing['Rental']['electric'] = l[this.Indices['electric']];
        listing['Rental']['water'] = l[this.Indices['water']];
        listing['Rental']['gas'] = l[this.Indices['gas']];
        listing['Rental']['heat'] = l[this.Indices['heat']];
        listing['Rental']['trash'] = l[this.Indices['trash']];
        listing['Rental']['cable'] = l[this.Indices['cable']];
        listing['Rental']['internet'] = l[this.Indices['internet']];
        listing['Rental']['utility_total_flat_rate'] = l[this.Indices['utility_total_flat_rate']];
        listing['Rental']['square_feet'] = l[this.Indices['square_feet']];
        listing['Rental']['air'] = l[this.Indices['air']];
        listing['Rental']['pets'] = l[this.Indices['pets']];
        private_parking = l[this.Indices['private_parking']];
        parking_type = l[this.Indices['parking_type']];
        parking_cost_type = l[this.Indices['parking_cost_type']];
        parking_cost = l[this.Indices['parking_cost']];
        listing['Rental']['parking_type'] = l[this.Indices['parking_type']];
        listing['Rental']['street_parking'] = l[this.Indices['street_parking']];
        listing['Rental']['private_parking'] = l[this.Indices['private_parking']];
        listing['Rental']['utility_total_flat_rate'] = l[this.Indices['utility_total_flat_rate']];
        listing['Rental']['parking_description'] = l[this.Indices['parking_description']];
        listing['Rental']['parking_amount'] = l[this.Indices['parking_amount']];
        listing['Rental']['furnished_type'] = l[this.Indices['furnished_type']];
        listing['Marker']['building_type_id'] = l[this.Indices['building_type_id']];
        listing['Marker']['alternate_name'] = l[this.Indices['alternate_name']];
        listing['User']['company_name'] = l[this.Indices['company_name']];
        listing['User']['phone'] = l[this.Indices['phone']];
        listing['User']['email'] = l[this.Indices['email']];
        listing['Rental']['website'] = l[this.Indices['website']];
        listing['Rental']['tv'] = l[this.Indices['tv']];
        listing['Rental']['balcony'] = l[this.Indices['balcony']];
        listing['Rental']['fridge'] = l[this.Indices['fridge']];
        listing['Rental']['storage'] = l[this.Indices['storage']];
        listing['Rental']['pool'] = l[this.Indices['pool']];
        listing['Rental']['hot_tub'] = l[this.Indices['hot_tub']];
        listing['Rental']['fitness_center'] = l[this.Indices['fitness_center']];
        listing['Rental']['game_room'] = l[this.Indices['game_room']];
        listing['Rental']['front_desk'] = l[this.Indices['front_desk']];
        listing['Rental']['security_system'] = l[this.Indices['security_system']];
        listing['Rental']['tanning_beds'] = l[this.Indices['tanning_beds']];
        listing['Rental']['study_lounge'] = l[this.Indices['study_lounge']];
        listing['Rental']['patio_deck'] = l[this.Indices['patio_deck']];
        listing['Rental']['yard_space'] = l[this.Indices['yard_space']];
        listing['Rental']['elevator'] = l[this.Indices['elevator']];
        listing['Rental']['deposit'] = l[this.Indices['deposit']];
        listing['Rental']['admin_amount'] = l[this.Indices['admin_amount']];
        listing['Rental']['furniture_amount'] = l[this.Indices['furniture_amount']];
        listing['Rental']['pets_amount'] = l[this.Indices['pets_amount']];
        listing['Rental']['upper_floor_amount'] = l[this.Indices['upper_floor_amount']];
        listing['Rental']['extra_occupant_amount'] = l[this.Indices['extra_occupant_amount']];
        listing['Rental']['amenity_amount'] = l[this.Indices['amenity_amount']];
        listing['Rental']['year_built'] = l[this.Indices['year_built']];
        listing['Rental']['min_occupancy'] = l[this.Indices['min_occupancy']];
        listing['Rental']['max_occupancy'] = l[this.Indices['max_occupancy']];
        listing['Rental']['unit_count'] = l[this.Indices['unit_count']];
        listing['Rental']['smoking'] = l[this.Indices['smoking']];
        listing['User']['street_address'] = l[this.Indices['user_street_address']];
        processedListings.push(listing);
        _results.push($.ajax({
          url: myBaseUrl + "Import/SaveListings",
          type: "POST",
          data: listing,
          context: this,
          async: false,
          success: function(response) {
            return console.log(response);
          }
        }));
      }
      return _results;
    };

    ImportManager.GetRent = function(min_rent, max_rent) {
      if (max_rent !== void 0 && max_rent !== null) {
        return max_rent;
      } else {
        return min_rent;
      }
    };

    ImportManager.delay = function(ms, func) {
      return setTimeout(func, ms);
    };

    ImportManager.escapeJSON = function(str) {
      return str.replace(/[\\]/g, '\\\\').replace(/[\"]/g, '\\\"').replace(/[\/]/g, '\\/').replace(/[\b]/g, '\\b').replace(/[\f]/g, '\\f').replace(/[\n]/g, '\\n').replace(/[\r]/g, '\\r').replace(/[\t]/g, '\\t');
    };

    return ImportManager;

  })();

}).call(this);
