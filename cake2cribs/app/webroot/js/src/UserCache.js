// Generated by CoffeeScript 1.4.0
(function() {

  A2Cribs.UserCache = (function() {

    function UserCache() {}

    UserCache.Cache = {};

    UserCache.Set = function(object) {
      var class_name;
      class_name = object.class_name;
      if (!(this.Cache[object.class_name] != null)) {
        this.Cache[object.class_name] = {};
      }
      return this.Cache[object.class_name][object.GetId()] = object;
    };

    UserCache.Get = function(object_type, id) {
      var item, list;
      if (this.Cache[object_type] != null) {
        if (id != null) {
          return this.Cache[object_type][id];
        } else {
          list = [];
          for (item in this.Cache[object_type]) {
            list.push(this.Cache[object_type][item]);
          }
          return list;
        }
      }
      if (id != null) {
        return null;
      } else {
        return [];
      }
    };

    UserCache.Remove = function(object_type, id) {
      if ((this.Cache[object_type] != null) && (id != null)) {
        return delete this.Cache[object_type][id];
      }
    };

    /*
    	Think of it as Get all {return_type} with a sorted_type_id that equals
    	sorted_id
    	Get all images with a listing_id of 3 would be
    	GetAllAssociatedObjects("image", "listing", listing_id)
    */


    UserCache.GetAllAssociatedObjects = function(return_type, sorted_type, sorted_id) {
      var item, list, return_id, return_list;
      if ((return_type != null) && (sorted_type != null) && (sorted_id != null)) {
        list = {};
        return_list = [];
        sorted_id = parseInt(sorted_id, 10);
        for (item in this.Cache[return_type]) {
          if (this.Cache[return_type][item]["" + sorted_type + "_id"] != null) {
            return_id = parseInt(this.Cache[return_type][item]["" + sorted_type + "_id"], 10);
            if (return_id === sorted_id) {
              list[this.Cache[return_type][item].GetId()] = true;
            }
          }
        }
        for (item in list) {
          return_list.push(this.Get(return_type, item));
        }
        return return_list;
      }
    };

    return UserCache;

  })();

}).call(this);
