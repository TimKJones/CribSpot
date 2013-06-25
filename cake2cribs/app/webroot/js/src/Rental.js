(function() {

  A2Cribs.Rental = (function() {

    function Rental(rental_id, listing_id, street_address, city, state, zip, unit_style_options, unit_style_type, unit_style_description, building_name, beds, min_occupancy, max_occupancy, building_type, rent, rent_negotiable, unit_count, start_date, alternate_start_date, lease_length, available, baths, air, parking_type, parking_spots, street_parking, furnished_type, pets_type, smoking, square_feet, year_built, electric, water, gas, heat, sewage, trash, cable, internet, utility_total_flat_rate, utility_estimate_winter, utility_estimate_summer, deposit, highlights, description, waitlist, waitlist_open_date, lease_office_address, contact_email, contact_phone, website) {
      this.rental_id = rental_id;
      this.listing_id = listing_id;
      this.street_address = street_address;
      this.city = city;
      this.state = state;
      this.zip = zip;
      this.unit_style_options = unit_style_options;
      this.unit_style_type = unit_style_type;
      this.unit_style_description = unit_style_description;
      this.building_name = building_name;
      this.beds = beds;
      this.min_occupancy = min_occupancy;
      this.max_occupancy = max_occupancy;
      this.building_type = building_type;
      this.rent = rent;
      this.rent_negotiable = rent_negotiable;
      this.unit_count = unit_count;
      this.start_date = start_date;
      this.alternate_start_date = alternate_start_date;
      this.lease_length = lease_length;
      this.available = available;
      this.baths = baths;
      this.air = air;
      this.parking_type = parking_type;
      this.parking_spots = parking_spots;
      this.street_parking = street_parking;
      this.furnished_type = furnished_type;
      this.pets_type = pets_type;
      this.smoking = smoking;
      this.square_feet = square_feet;
      this.year_built = year_built;
      this.electric = electric;
      this.water = water;
      this.gas = gas;
      this.heat = heat;
      this.sewage = sewage;
      this.trash = trash;
      this.cable = cable;
      this.internet = internet;
      this.utility_total_flat_rate = utility_total_flat_rate;
      this.utility_estimate_winter = utility_estimate_winter;
      this.utility_estimate_summer = utility_estimate_summer;
      this.deposit = deposit;
      this.highlights = highlights;
      this.description = description;
      this.waitlist = waitlist;
      this.waitlist_open_date = waitlist_open_date;
      this.lease_office_address = lease_office_address;
      this.contact_email = contact_email;
      this.contact_phone = contact_phone;
      this.website = website;
    }

    Rental.Save = function() {
      var data,
        _this = this;
      data = {
        Rental: {
          rental_id: A2Cribs.UILayer.Rentals.rental_id(),
          listing_id: A2Cribs.UILayer.Rentals.listing_id(),
          street_address: A2Cribs.UILayer.Rentals.street_address(),
          city: A2Cribs.UILayer.Rentals.city(),
          state: A2Cribs.UILayer.Rentals.state(),
          zipcode: A2Cribs.UILayer.Rentals.zipcode(),
          unit_style_options: A2Cribs.UILayer.Rentals.unit_style_options(),
          unit_style_type: A2Cribs.UILayer.Rentals.unit_style_type(),
          unit_style_description: A2Cribs.UILayer.Rentals.unit_style_description(),
          building_name: A2Cribs.UILayer.Rentals.building_name(),
          beds: A2Cribs.UILayer.Rentals.beds(),
          min_occupancy: A2Cribs.UILayer.Rentals.min_occupancy(),
          max_occupancy: A2Cribs.UILayer.Rentals.max_occupancy(),
          building_type: A2Cribs.UILayer.Rentals.building_type(),
          rent: A2Cribs.UILayer.Rentals.rent(),
          rent_negotiable: A2Cribs.UILayer.Rentals.rent_negotiable(),
          unit_count: A2Cribs.UILayer.Rentals.unit_count(),
          start_date: A2Cribs.UILayer.Rentals.start_date(),
          alternate_start_date: A2Cribs.UILayer.Rentals.alternate_start_date(),
          lease_length: A2Cribs.UILayer.Rentals.lease_length(),
          available: A2Cribs.UILayer.Rentals.available(),
          baths: A2Cribs.UILayer.Rentals.baths(),
          air: A2Cribs.UILayer.Rentals.air(),
          parking_type: A2Cribs.UILayer.Rentals.parking_type(),
          parking_spots: A2Cribs.UILayer.Rentals.parking_spots(),
          street_parking: A2Cribs.UILayer.Rentals.street_parking(),
          furnished_type: A2Cribs.UILayer.Rentals.furnished_type(),
          pets_type: A2Cribs.UILayer.Rentals.pets_type(),
          smoking: A2Cribs.UILayer.Rentals.smoking(),
          square_feet: A2Cribs.UILayer.Rentals.square_feet(),
          year_built: A2Cribs.UILayer.Rentals.year_built(),
          electric: A2Cribs.UILayer.Rentals.electric(),
          water: A2Cribs.UILayer.Rentals.water(),
          gas: A2Cribs.UILayer.Rentals.gas(),
          heat: A2Cribs.UILayer.Rentals.heat(),
          sewage: A2Cribs.UILayer.Rentals.sewage(),
          trash: A2Cribs.UILayer.Rentals.trash(),
          cable: A2Cribs.UILayer.Rentals.cable(),
          internet: A2Cribs.UILayer.Rentals.internet(),
          utility_total_flat_rate: A2Cribs.UILayer.Rentals.utility_total_flat_rate(),
          utility_estimate_winter: A2Cribs.UILayer.Rentals.utility_estimate_winter(),
          utility_estimate_summer: A2Cribs.UILayer.Rentals.utility_estimate_summer(),
          deposit: A2Cribs.UILayer.Rentals.deposit(),
          highlights: A2Cribs.UILayer.Rentals.highlights(),
          description: A2Cribs.UILayer.Rentals.description(),
          waitlist: A2Cribs.UILayer.Rentals.waitlist(),
          waitlist_open_date: A2Cribs.UILayer.Rentals.waitlist_open_date(),
          lease_office_address: A2Cribs.UILayer.Rentals.lease_office_address(),
          contact_email: A2Cribs.UILayer.Rentals.contact_email(),
          contact_phone: A2Cribs.UILayer.Rentals.contact_phone(),
          website: A2Cribs.UILayer.Rentals.website()
        },
        Fees: A2Cribs.UILayer.Fees.GetFees()
      };
      return $.ajax({
        url: myBaseUrl + "rentals/Save",
        type: "POST",
        data: data,
        success: function(response) {
          response = JSON.parse(response);
          if (response.success !== null) {
            return alert("Success!");
          } else {
            return alert(response.error);
          }
        }
      });
    };

    Rental.prototype.SetupUI = function() {
      /*
      		********************* TODO **********************
      */
    };

    Rental.prototype.Open = function(rental_ids) {
      /*
      		********************* TODO **********************
      */
    };

    Rental.prototype.Save = function() {
      /*
      		********************* TODO **********************
      */
    };

    Rental.prototype.Copy = function(rental_ids) {
      /*
      		********************* TODO (Not first priority) *
      */
    };

    Rental.prototype.Delete = function(rental_ids) {
      /*
      		********************* TODO **********************
      */
    };

    Rental.prototype.Create = function() {
      /*
      		********************* TODO **********************
      */
    };

    Rental.prototype.PopulateGrid = function(rental_ids) {
      /*
      		********************* TODO **********************
      */
    };

    return Rental;

  })();

}).call(this);
