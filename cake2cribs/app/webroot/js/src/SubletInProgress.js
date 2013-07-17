(function() {

  A2Cribs.SubletInProgress = (function() {

    function SubletInProgress() {
      this.Sublet = {
        id: null,
        university_id: null,
        university_name: null,
        building_type_id: null,
        date_begin: null,
        date_end: null,
        number_bedrooms: null,
        price_per_bedroom: null,
        payment_type_id: null,
        short_description: null,
        description: null,
        bathroom_type_id: null,
        utility_type_id: null,
        utility_cost: null,
        deposit_amount: null,
        additional_fees_description: null,
        additional_fees_amount: null,
        unit_number: null,
        flexible_dates: null,
        furnished_type_id: null,
        ac: null,
        parking: null
      };
      this.Marker = {
        marker_id: null,
        alternate_name: null,
        street_address: null,
        building_type_id: null,
        city: null,
        state: null,
        zip: null,
        latitude: null,
        longitude: null
      };
      this.Housemate = {
        id: null,
        quantity: null,
        enrolled: null,
        student_type_id: null,
        major: null,
        gender_type_id: null,
        type: null
      };
    }

    return SubletInProgress;

  })();

}).call(this);
