(function() {

  A2Cribs.PropertyManager = (function() {

    function PropertyManager(property_manager_id, user_id, lease_office_address, contact_email, contact_phone, website) {
      this.property_manager_id = property_manager_id;
      this.user_id = user_id;
      this.lease_office_address = lease_office_address;
      this.contact_email = contact_email;
      this.contact_phone = contact_phone;
      this.website = website;
    }

    return PropertyManager;

  })();

}).call(this);
