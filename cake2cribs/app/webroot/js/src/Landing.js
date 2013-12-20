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
      var key, url_name, val, _ref, _ref1, _ref2;
      _ref = university['University'];
      for (key in _ref) {
        val = _ref[key];
        $("#school_page").find("." + key).hide().text(val).fadeIn();
      }
      swap_backgrounds(university['University']['id']);
      url_name = university['University']['name'].split(" ").join("_");
      $("#map_link").attr("href", "/rental/" + url_name);
      $(".background_source").attr("href", university['University']['background_source']);
      if ((_ref1 = university['University']['logo_path']) != null ? _ref1.length : void 0) {
        $(".school_logo").show().css("background-image", "url(" + university['University']['logo_path'] + ")");
      } else {
        $(".school_logo").hide();
      }
      if ((_ref2 = university.University.founder_image) != null ? _ref2.length : void 0) {
        $(".founder_photo").attr("src", university.University.founder_image);
        $(".founder_title").text("" + university['University']['name'] + " Founder");
        return $("#founder_box").fadeIn();
      } else {
        return $("#founder_box").hide();
      }
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
        return A2Cribs.ShareManager.ShowShareModal("", "College housing sucks! Tell your friends how easy finding the perfect house can be. Share Cribspot!", "landing page share");
      });
      if (((_ref = this.locations) != null ? _ref.length : void 0) != null) {
        random_school = Math.floor(Math.random() * this.locations.length);
        set_school(this.locations[random_school]);
      }
      return $("#school_selector").change(function(event) {
        var university, university_id, _i, _len, _ref1;
        university_id = $(event.currentTarget).val();
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
