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
        rental_id: A2Cribs.UI_Rentals.rental_id(),
        listing_id: A2Cribs.UI_Rentals.listing_id(),
        street_address: A2Cribs.UI_Rentals.street_address(),
        city: A2Cribs.UI_Rentals.city(),
        state: A2Cribs.UI_Rentals.state(),
        zipcode: A2Cribs.UI_Rentals.zipcode(),
        unit_style_options: A2Cribs.UI_Rentals.unit_style_options(),
        unit_style_type: A2Cribs.UI_Rentals.unit_style_type(),
        unit_style_description: A2Cribs.UI_Rentals.unit_style_description(),
        building_name: A2Cribs.UI_Rentals.building_name(),
        beds: A2Cribs.UI_Rentals.beds(),
        min_occupancy: A2Cribs.UI_Rentals.min_occupancy(),
        max_occupancy: A2Cribs.UI_Rentals.max_occupancy(),
        building_type: A2Cribs.UI_Rentals.building_type(),
        rent: A2Cribs.UI_Rentals.rent(),
        rent_negotiable: A2Cribs.UI_Rentals.rent_negotiable(),
        unit_count: A2Cribs.UI_Rentals.unit_count(),
        start_date: A2Cribs.UI_Rentals.start_date(),
        alternate_start_date: A2Cribs.UI_Rentals.alternate_start_date(),
        lease_length: A2Cribs.UI_Rentals.lease_length(),
        available: A2Cribs.UI_Rentals.available(),
        baths: A2Cribs.UI_Rentals.baths(),
        air: A2Cribs.UI_Rentals.air(),
        parking_type: A2Cribs.UI_Rentals.parking_type(),
        parking_spots: A2Cribs.UI_Rentals.parking_spots(),
        street_parking: A2Cribs.UI_Rentals.street_parking(),
        furnished_type: A2Cribs.UI_Rentals.furnished_type(),
        pets_type: A2Cribs.UI_Rentals.pets_type(),
        smoking: A2Cribs.UI_Rentals.smoking(),
        square_feet: A2Cribs.UI_Rentals.square_feet(),
        year_built: A2Cribs.UI_Rentals.year_built(),
        electric: A2Cribs.UI_Rentals.electric(),
        water: A2Cribs.UI_Rentals.water(),
        gas: A2Cribs.UI_Rentals.gas(),
        heat: A2Cribs.UI_Rentals.heat(),
        sewage: A2Cribs.UI_Rentals.sewage(),
        trash: A2Cribs.UI_Rentals.trash(),
        cable: A2Cribs.UI_Rentals.cable(),
        internet: A2Cribs.UI_Rentals.internet(),
        utility_total_flat_rate: A2Cribs.UI_Rentals.utility_total_flat_rate(),
        utility_estimate_winter: A2Cribs.UI_Rentals.utility_estimate_winter(),
        utility_estimate_summer: A2Cribs.UI_Rentals.utility_estimate_summer(),
        deposit: A2Cribs.UI_Rentals.deposit(),
        highlights: A2Cribs.UI_Rentals.highlights(),
        description: A2Cribs.UI_Rentals.description(),
        waitlist: A2Cribs.UI_Rentals.waitlist(),
        waitlist_open_date: A2Cribs.UI_Rentals.waitlist_open_date(),
        lease_office_address: A2Cribs.UI_Rentals.lease_office_address(),
        contact_email: A2Cribs.UI_Rentals.contact_email(),
        contact_phone: A2Cribs.UI_Rentals.contact_phone(),
        website: A2Cribs.UI_Rentals.website()
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
