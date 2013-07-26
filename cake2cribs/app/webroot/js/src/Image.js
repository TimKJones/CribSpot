(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  A2Cribs.Image = (function(_super) {

    __extends(Image, _super);

    function Image(image) {
      Image.__super__.constructor.call(this, "image", image);
    }

    return Image;

  })(A2Cribs.Object);

}).call(this);
