(function() {

  A2Cribs.FullListing = (function() {

    function FullListing() {}

    FullListing.SetupUI = function(listing_id) {
      var _this = this;
      this.listing_id = listing_id;
      this.div = $(".full_page");
      this.div.find(".show_scheduling").click(function(event) {
        var _ref;
        if (((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0) === true) {
          if (!($(event.currentTarget).attr("href") != null)) {
            $("#scheduling_tour_tab").click();
          } else {
            $(event.currentTarget).tab('show');
            $(document).trigger("track_event", ["Full Page", "Schedule Tour Clicked", "", listing_id]);
          }
        } else {
          $("#signup_modal").modal("show").find(".signup_message").text("Please sign in to schedule a tour.");
          $(document).trigger("track_event", ["Login", "Login required", "Schedule Tour", listing_id]);
        }
        return event.preventDefault();
      });
      this.div.find(".image_preview").click(function(event) {
        var image;
        image = $(event.delegateTarget).css("background-image");
        _this.div.find(".image_preview.active").removeClass("active");
        $(event.delegateTarget).addClass("active");
        return _this.div.find("#main_photo").css("background-image", image);
      });
      this.div.find(".page_right").click(function(event) {
        var next_photo;
        if (_this.div.find(".image_preview.active").next().length) {
          next_photo = _this.div.find(".image_preview.active").next();
          _this.div.find(".image_preview.active").removeClass("active");
          next_photo.addClass("active");
          return _this.div.find("#main_photo").css("background-image", next_photo.css("background-image"));
        }
      });
      this.div.find(".page_left").click(function(event) {
        var next_photo;
        if (_this.div.find(".image_preview.active").prev().length) {
          next_photo = _this.div.find(".image_preview.active").prev();
          _this.div.find(".image_preview.active").removeClass("active");
          next_photo.addClass("active");
          return _this.div.find("#main_photo").css("background-image", next_photo.css("background-image"));
        }
      });
      this.div.find("#contact_owner").click(function() {
        var _ref;
        if (((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0) === true) {
          $(document).trigger("track_event", ["Full Page", "Contact Owner Clicked", "", listing_id]);
          _this.div.find("#contact_owner").hide();
          return _this.div.find("#contact_message").slideDown();
        } else {
          $("#signup_modal").modal("show").find(".signup_message").text("Please sign in to contact the owner.");
          return $(document).trigger("track_event", ["Login", "Login required", "Contact Owner", listing_id]);
        }
      });
      this.div.find("#message_cancel").click(function() {
        return _this.div.find("#contact_message").slideUp('fast', function() {
          return _this.div.find("#contact_owner").show();
        });
      });
      return this.div.find("#message_send").click(function() {
        $("#message_send").button("loading");
        $(document).trigger("track_event", ["Message", "Sending Message", "", listing_id]);
        $("#loader").show();
        return $.ajax({
          url: myBaseUrl + "Messages/messageSublet",
          type: "POST",
          data: {
            listing_id: _this.listing_id,
            message_body: $("#message_area").val()
          },
          success: function(response) {
            var data;
            data = JSON.parse(response);
            if (data.success) {
              $("#message_area").val("");
              A2Cribs.UIManager.Success("Message Sent!");
              $(document).trigger("track_event", ["Message", "Message Sent", "", listing_id]);
            } else {
              if (data.message != null) {
                A2Cribs.UIManager.Error(data.message);
              } else {
                A2Cribs.UIManager.Error("Message Failed! Please Try Again.");
              }
              $(document).trigger("track_event", ["Message", "Message Failed", "", listing_id]);
            }
            return $("#message_send").button("reset");
          },
          complete: function() {
            return $("#loader").hide();
          }
        });
      });
    };

    FullListing.Directive = function(directive) {
      if (directive.contact_owner != null) {
        return this.div.find("#contact_owner").click();
      } else if (directive.schedule != null) {
        return this.div.find("#scheduling_tour_tab").click();
      }
    };

    return FullListing;

  })();

}).call(this);
