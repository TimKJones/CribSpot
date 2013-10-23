// Generated by CoffeeScript 1.4.0
(function() {

  A2Cribs.FullListing = (function() {

    function FullListing() {}

    FullListing.SetupUI = function(listing_id) {
      var _this = this;
      this.listing_id = listing_id;
      this.div = $(".full_page");
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
        A2Cribs.MixPanel.Event("Contact PM", {
          "listing_id": _this.listing_id
        });
        if (((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0) === true) {
          _this.div.find("#contact_owner").hide();
          return _this.div.find("#contact_message").slideDown();
        } else {
          $("#signup_modal").modal("show").find(".signup_message").text("Please sign in to contact the owner.");
          return A2Cribs.MixPanel.Event("login required", {
            "listing_id": _this.listing_id
          });
        }
      });
      this.div.find("#message_cancel").click(function() {
        return _this.div.find("#contact_message").slideUp('fast', function() {
          return _this.div.find("#contact_owner").show();
        });
      });
      return this.div.find("#message_send").click(function() {
        $("#message_send").button("loading");
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
              A2Cribs.MixPanel.Event("message sent", {
                "listing_id": _this.listing_id
              });
            } else {
              if (data.message != null) {
                A2Cribs.UIManager.Error(data.message);
              } else {
                A2Cribs.UIManager.Error("Message Failed! Please Try Again.");
              }
            }
            return $("#message_send").button("reset");
          }
        });
      });
    };

    FullListing.Directive = function(directive) {
      if (directive.contact_owner != null) {
        return this.div.find("#contact_owner").click();
      }
    };

    return FullListing;

  })();

}).call(this);
