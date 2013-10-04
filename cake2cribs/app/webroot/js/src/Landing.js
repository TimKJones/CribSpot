// Generated by CoffeeScript 1.4.0
(function() {

  A2Cribs.Landing = (function() {
    var set_school, swap_backgrounds;

    function Landing() {}

    swap_backgrounds = function(university_id) {
      var new_background, old_background;
      old_background = $(".current_background");
      if (old_background.attr("data-university") !== university_id) {
        new_background = $("img[data-university='" + university_id + "'].school_background");
        new_background.css("opacity", "0.0").zIndex(-1).addClass("current_background");
        old_background.zIndex(-2).removeClass("current_background");
        return new_background.animate({
          "opacity": 1.0
        }, 1200, function() {
          return old_background.zIndex(-3);
        });
      }
    };

    set_school = function(university) {
      var key, url_name, val, _ref;
      _ref = university['University'];
      for (key in _ref) {
        val = _ref[key];
        $("#school_page").find("." + key).hide().text(val).fadeIn();
      }
      swap_backgrounds(university['University']['id']);
      url_name = university['University']['name'].split(" ").join("_");
      $("#map_link").attr("href", "/rental/" + url_name);
      $(".background_source").attr("href", university['University']['background_source']);
      return $(".school_logo").css("background-image", "url(" + university['University']['logo_path'] + ")");
    };

    Landing.Init = function(locations) {
      var random_school, _ref,
        _this = this;
      this.locations = locations;
      $(window).scroll(function() {
        var scrolled;
        scrolled = $(window).scrollTop();
        return $('.current_background').css('top', (0 - (scrolled * .25)) + 'px');
      });
      $("#friends_invite").click(function() {
        A2Cribs.MixPanel.Event("Invite Friends", null);
        return typeof FB !== "undefined" && FB !== null ? FB.ui({
          method: 'apprequests',
          message: 'Join the Movement. All the College Rentals. All in One Spot.'
        }) : void 0;
      });
      if (((_ref = this.locations) != null ? _ref.length : void 0) != null) {
        random_school = Math.floor(Math.random() * this.locations.length);
        set_school(this.locations[random_school]);
      }
      return $(".university_link").click(function(event) {
        var university, university_id, _i, _len, _ref1;
        university_id = $(event.delegateTarget).attr("data-university");
        _ref1 = _this.locations;
        for (_i = 0, _len = _ref1.length; _i < _len; _i++) {
          university = _ref1[_i];
          if (university['University']['id'] === university_id) {
            _this.Current_University = university;
            break;
          }
        }
        if (_this.Current_University != null) {
          set_school(_this.Current_University);
        }
        return $('html, body').animate({
          scrollTop: $("#school_page").offset().top
        }, 1200);
      });
    };

    return Landing;

  })();

}).call(this);
