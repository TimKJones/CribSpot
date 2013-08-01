(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  A2Cribs.Rental = (function(_super) {
    var data;

    __extends(Rental, _super);

    function Rental(rental) {
      var date, dates, index, _i, _len;
      Rental.__super__.constructor.call(this, "rental", rental);
      dates = ["start_date", "end_date", "alternate_start_date"];
      for (_i = 0, _len = dates.length; _i < _len; _i++) {
        date = dates[_i];
        if (this[date]) {
          if ((index = this[date].indexOf(" ")) !== -1) {
            this[date] = this[date].substring(0, index);
          }
        }
      }
    }

    Rental.prototype.GetId = function(id) {
      return parseInt(this["listing_id"], 10);
    };

    Rental.Template = data = {
      Listing: {
        listing_type: 0
      },
      Rental: {
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
        max_occupancy: 100,
        building_type: A2Cribs.UILayer.Rentals.building_type(),
        rent: A2Cribs.UILayer.Rentals.rent(),
        rent_negotiable: A2Cribs.UILayer.Rentals.rent_negotiable(),
        unit_count: A2Cribs.UILayer.Rentals.unit_count(),
        start_date: A2Cribs.UILayer.Rentals.start_date(),
        alternate_start_date: A2Cribs.UILayer.Rentals.alternate_start_date(),
        end_date: A2Cribs.UILayer.Rentals.end_date(),
        dates_negotiable: 0,
        available: A2Cribs.UILayer.Rentals.available(),
        baths: 555,
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
        description: "Its a new listing!!!!!",
        waitlist: A2Cribs.UILayer.Rentals.waitlist(),
        waitlist_open_date: A2Cribs.UILayer.Rentals.waitlist_open_date(),
        lease_office_address: A2Cribs.UILayer.Rentals.lease_office_address(),
        contact_email: A2Cribs.UILayer.Rentals.contact_email(),
        contact_phone: A2Cribs.UILayer.Rentals.contact_phone(),
        website: A2Cribs.UILayer.Rentals.website()
      },
      Image: {
        0: {
          image_id: 275,
          caption: "herefdf",
          is_primary: 0
        },
        1: {
          image_id: 276,
          caption: "heres the second one",
          is_primary: 1
        }
      }
    };

    Rental.Required_Fields = {
      unit_style_options: "overview_grid",
      unit_style_type: "overview_grid",
      unit_style_description: "overview_grid",
      beds: "overview_grid",
      min_occupancy: "overview_grid",
      max_occupancy: "overview_grid",
      rent: "overview_grid",
      unit_count: "overview_grid",
      start_date: "overview_grid",
      end_date: "overview_grid",
      available: "overview_grid",
      highlights: "description_grid",
      contact_email: "contact_grid",
      contact_phone: "contact_grid",
      website: "contact_grid"
    };

    return Rental;

  })(A2Cribs.Object);

}).call(this);
