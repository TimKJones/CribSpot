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

    /*@TwoDigits: (date) ->
    		if 0 <= date && date < 10
    			return "0" + date.toString()
    		if -10 < date && date < 0
    			return "-0" + (-1*date).toString()
    		return date.toString();
    
    	@GetMysqlDate: (date) ->
    		return date.getUTCFullYear() + "-" + @TwoDigits(1 + date.getUTCMonth()) + "-" + @TwoDigits(date.getUTCDate()) + " " + @TwoDigits(date.getUTCHours()) + ":" + @TwoDigits(date.getUTCMinutes()) + ":" + @TwoDigits(date.getUTCSeconds());
    */

    Rental.GetFormattedDate = function(date) {
      var day, month, year;
      year = date.getUTCFullYear();
      month = date.getMonth() + 1;
      day = date.getDate();
      return year + '-' + month + '-' + day;
    };

    Rental.Save = function() {
      var data,
        _this = this;
      data = {
        rental_id: 1,
        listing_id: 2,
        street_address: "521 Linden St",
        city: "Ann Arbor",
        state: "MI",
        zipcode: "48104",
        unit_style_options: 2,
        unit_style_type: "NA",
        unit_style_description: "NA",
        building_name: "",
        beds: 6,
        min_occupancy: "",
        max_occupancy: 6,
        building_type: 2,
        rent: 3600,
        rent_negotiable: 0,
        unit_count: 1,
        start_date: this.GetFormattedDate(new Date("09-02-2013")),
        alternate_start_date: "",
        lease_length: 12,
        available: 1,
        baths: 2,
        air: 1,
        parking_type: 1,
        parking_spots: 6,
        street_parking: 0,
        furnished_type: 0,
        pets_type: 1,
        smoking: 1,
        square_feet: 2000,
        year_built: 1944,
        electric: 1,
        water: 1,
        gas: 1,
        heat: 1,
        sewage: 1,
        trash: 1,
        cable: 1,
        internet: 1,
        utility_total_flat_rate: 0,
        utility_estimate_winter: 250,
        utility_estimate_summer: 200,
        deposit: 900,
        highlights: "Its a really fun place",
        description: "This is a longer description about the place",
        waitlist: 1,
        waitlist_open_date: "",
        lease_office_address: "Jonah Copi's place",
        contact_email: "email@address.com",
        contact_phone: "5555555555",
        website: "www.cribspot.com"
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

    return Rental;

  })();

}).call(this);
