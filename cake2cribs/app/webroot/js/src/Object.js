(function() {

  A2Cribs.Object = (function() {

    function Object(class_name, a2_object) {
      var key, value;
      this.class_name = class_name != null ? class_name : "object";
      for (key in a2_object) {
        value = a2_object[key];
        if (value != null) this[key] = value;
      }
    }

    Object.prototype.GetId = function(id) {
      return parseInt(this["" + this.class_name + "_id"], 10);
    };

    Object.prototype.GetObject = function() {
      var key, return_object, value;
      return_object = {};
      for (key in this) {
        value = this[key];
        if (typeof value !== "function") {
          if (typeof value === "boolean") value = +value;
          return_object[key] = value;
        }
      }
      return return_object;
    };

    return Object;

  })();

}).call(this);
