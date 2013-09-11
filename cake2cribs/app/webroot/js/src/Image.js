// Generated by CoffeeScript 1.4.0
(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  A2Cribs.Image = (function(_super) {

    __extends(Image, _super);

    /*
    	Image is an array of all the images associated with a listing
    */


    function Image(image) {
      var i, image_object, _i, _len, _ref;
      if (image.length !== 0) {
        this.class_name = "image";
        this.image_array = image;
        _ref = this.image_array;
        for (i = _i = 0, _len = _ref.length; _i < _len; i = ++_i) {
          image_object = _ref[i];
          if (image_object.is_primary) {
            this.primary = i;
          }
        }
        this.listing_id = this.image_array[0].listing_id;
      }
    }

    Image.prototype.GetId = function() {
      return this.listing_id;
    };

    Image.prototype.GetPrimary = function(field) {
      if (field == null) {
        field = 'image_path';
      }
      if (this.primary != null) {
        return this.image_array[this.primary][field];
      }
    };

    Image.prototype.GetImages = function() {
      return this.image_array;
    };

    Image.prototype.GetObject = function() {
      var image, img_copy, key, return_array, value, _i, _len, _ref;
      return_array = [];
      _ref = this.image_array;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        image = _ref[_i];
        img_copy = {};
        for (key in image) {
          value = image[key];
          if (typeof value !== "function") {
            if (typeof value === "boolean") {
              value = +value;
            }
            img_copy[key] = value;
          }
        }
        return_array.push(img_copy);
      }
      return return_array;
    };

    return Image;

  })(A2Cribs.Object);

}).call(this);
