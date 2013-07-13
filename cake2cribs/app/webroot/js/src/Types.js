(function() {

  A2Cribs.Types = (function() {

    function Types() {}

    Types.LISTING_TYPE_RENTAL = 0;

    Types.LISTING_TYPE_SUBLET = 1;

    Types.LISTING_TYPE_PARKING = 2;

    Types.UNIT_STYLE_OPTIONS = {
      0: "Style",
      1: "Unit",
      2: "Entire Unit"
    };

    Types.BUILDING_TYPE = {
      0: "House",
      1: "Apartment",
      2: "Duplex"
    };

    Types.AIR = {
      0: "Central",
      1: "Wall Unit",
      2: "None"
    };

    Types.PARKING = {
      0: "Parking Lot",
      1: "Driveway",
      2: "Garage",
      2: "Off Site"
    };

    Types.FURNISHED = {
      0: "Fully",
      1: "Partialy",
      2: "No"
    };

    Types.PETS = {
      0: "Cats Only",
      1: "Dogs Only",
      2: "Cats and Dogs"
    };

    Types.WASHER_DRYER = {
      0: "In Unit",
      1: "On-Site Free",
      2: "On-Site Coin-Operated",
      3: "Off-Site"
    };

    Types.UTILITIES_INCLUDED = {
      0: "No",
      1: "Yes",
      2: "Flat Rate"
    };

    return Types;

  })();

}).call(this);