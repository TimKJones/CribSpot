(function() {

  A2Cribs.UserCache = (function() {
    var _cache_object, _get,
      _this = this;

    function UserCache() {}

    UserCache.Cache = {};

    _get = function(object_type, id, callback) {
      var url,
        _this = this;
      if (object_type === "listing" || object_type === "rental") {
        url = myBaseUrl + "Listings/GetListing/" + id;
      }
      return $.ajax({
        url: url,
        type: "GET",
        success: function(data) {
          return callback != null ? callback.success(JSON.parse(data)) : void 0;
        },
        error: function() {
          return callback != null ? callback.error() : void 0;
        }
      });
    };

    _cache_object = function(object) {
      var a2_object, key, old_object, value, _results;
      _results = [];
      for (key in object) {
        value = object[key];
        if (A2Cribs[key] != null) {
          a2_object = new A2Cribs[key](value);
          old_object = UserCache.Get(key.toLowerCase(), a2_object.GetId());
          if ((old_object != null) && !(old_object.length != null)) {
            a2_object = old_object.Update(value);
          }
          _results.push(UserCache.Set(a2_object));
        } else {
          _results.push(void 0);
        }
      }
      return _results;
    };

    /*
    	Cache Data
    	Caches an array of Listing objects
    */

    UserCache.CacheData = function(object) {
      var item, _i, _len, _results;
      if (object instanceof Array) {
        _results = [];
        for (_i = 0, _len = object.length; _i < _len; _i++) {
          item = object[_i];
          _results.push(_cache_object(item));
        }
        return _results;
      } else {
        return _cache_object(object);
      }
    };

    /*
    	Get Listing
    	Retrieves a listing_type (rental, sublet, parking) by a listing id
    	Uses deferred object to fetch from async
    */

    UserCache.GetListing = function(listing_type, listing_id) {
      var deferred, listing,
        _this = this;
      deferred = new $.Deferred();
      if (!(listing_type != null) || !(listing_id != null)) {
        return deferred.reject();
      }
      listing = A2Cribs.UserCache.Get(listing_type, listing_id);
      if (listing != null ? listing.IsComplete() : void 0) {
        return deferred.resolve(listing);
      }
      $.ajax({
        url: myBaseUrl + "Listings/GetListing/" + listing_id,
        type: "GET",
        success: function(data) {
          var response_data;
          response_data = JSON.parse(data);
          _this.CacheData(response_data);
          listing = _this.Get(listing_type, listing_id);
          return deferred.resolve(listing);
        },
        error: function() {
          return deferred.reject();
        }
      });
      return deferred.promise();
    };

    UserCache.GetDiferred = function(object_type, id) {
      var deferred, item,
        _this = this;
      deferred = new $.Deferred();
      item = this.Get(object_type, id);
      if (!(item != null) || !item.IsComplete()) {
        _get(object_type, id, {
          success: function(data) {
            var key, listing_object, value, _i, _len;
            for (_i = 0, _len = data.length; _i < _len; _i++) {
              listing_object = data[_i];
              for (key in listing_object) {
                value = listing_object[key];
                if (A2Cribs[key] != null) _this.Set(new A2Cribs[key](value));
              }
            }
            return item = _this.Get(object_type, id);
          },
          error: function() {
            return deferred.resolve(null);
          }
        });
        return deferred.promise();
      } else {
        return deferred.resolve(item);
      }
    };

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

  }).call(this);

}).call(this);
