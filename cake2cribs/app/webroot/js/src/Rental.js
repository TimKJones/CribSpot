(function() {

  A2Cribs.Rental = (function() {
    var data;

    function Rental(rental_id, listing_id, street_address, city, state, zip, unit_style_options, unit_style_type, unit_style_description, building_name, beds, min_occupancy, max_occupancy, building_type, rent, rent_negotiable, unit_count, start_date, alternate_start_date, lease_length, available, baths, air, parking_type, parking_spots, street_parking, furnished_type, pets_type, smoking, tv, balcony, fridge, storage, square_feet, year_built, pool, hot_tub, fitness_center, game_room, front_desk, security_system, tanning_beds, study_lounge, patio_deck, yard_space, elevator, electric, water, gas, heat, sewage, trash, cable, internet, utility_total_flat_rate, utility_estimate_winter, utility_estimate_summer, deposit, highlights, description, waitlist, waitlist_open_date, lease_office_address, contact_email, contact_phone, website) {
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
      this.tv = tv;
      this.balcony = balcony;
      this.fridge = fridge;
      this.storage = storage;
      this.square_feet = square_feet;
      this.year_built = year_built;
      this.pool = pool;
      this.hot_tub = hot_tub;
      this.fitness_center = fitness_center;
      this.game_room = game_room;
      this.front_desk = front_desk;
      this.security_system = security_system;
      this.tanning_beds = tanning_beds;
      this.study_lounge = study_lounge;
      this.patio_deck = patio_deck;
      this.yard_space = yard_space;
      this.elevator = elevator;
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

    Rental.Template = data = {
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
        tv: 1,
        balcony: 1,
        fridge: 1,
        storage: 1,
        square_feet: A2Cribs.UILayer.Rentals.square_feet(),
        year_built: A2Cribs.UILayer.Rentals.year_built(),
        pool: 1,
        hot_tub: 1,
        fitness_center: 1,
        game_room: 1,
        front_desk: 1,
        security_system: 1,
        tanning_beds: 1,
        study_lounge: 1,
        patio_deck: 1,
        yard_space: 1,
        elevator: 1,
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

    return Rental;

  })();

}).call(this);
