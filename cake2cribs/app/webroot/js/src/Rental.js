(function() {

  A2Cribs.Rental = (function() {

    function Rental(rental_id, listing_id, address, unit_style_options, unit_style_type, unit_style_description, building_name, beds, min_occupancy, max_occupancy, building_type, rent, rent_negotiable, unit_count, start_date, alternate_start_date, lease_length, available, baths, air, parking_type, parking_spots, street_parking, furnished_type, pets_type, smoking, square_feet, year_built, electric, water, gas, heat, sewage, trash, cable, internet, utility_total_flat_rate, utility_estimate_winter, utility_estimate_summer, deposit, highlights, description, waitlist, waitlist_open_date, lease_office_address, contact_email, contact_phone, website) {
      this.rental_id = rental_id;
      this.listing_id = listing_id;
      this.address = address;
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

    return Rental;

  })();

}).call(this);
