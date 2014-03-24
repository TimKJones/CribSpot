(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  A2Cribs.User = (function(_super) {

    __extends(User, _super);

    function User(user) {
      User.__super__.constructor.call(this, "user", user);
    }

    User.prototype.GetId = function() {
      return this.id;
    };

    return User;

  })(A2Cribs.Object);

}).call(this);
