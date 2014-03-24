(function() {

  A2Cribs.ShareManager = (function() {
    var _this = this;

    function ShareManager() {}

    /*
    	Creates a listing url from its individual components
    */

    ShareManager.GetShareUrl = function(listing_id, street_address, city, state, zip) {
      var url;
      if (listing_id === null || street_address === null || city === null || state === null || zip === null) {
        return null;
      }
      street_address = street_address.split(' ').join('-');
      city = city.split(' ').join('-');
      url = 'https://cribspot.com/listing/' + listing_id;
      $(document).trigger("track_event", ["Share", "URL Copied", "", listing_id]);
      return url;
    };

    /*
    	Brings up a dialog box for user to add a message and then post to their facebook timeline
    */

    ShareManager.ShareListingOnFacebook = function(listing_id, street_address, city, state, zip, description, building_name) {
      var caption, fbObj, url;
      if (description == null) description = null;
      if (building_name == null) building_name = null;
      url = this.GetShareUrl(listing_id, street_address, city, state, zip);
      caption = 'Check out this listing on Cribspot!';
      if (building_name === null) {
        building_name = street_address;
      } else {
        caption = street_address;
      }
      $(document).trigger("track_event", ["Share", "Listing on FB", "", listing_id]);
      fbObj = {
        method: 'feed',
        link: url,
        picture: 'https://s3-us-west-2.amazonaws.com/cribspot-img/upright_logo.png',
        name: building_name,
        caption: caption
      };
      if (description !== null) fbObj['description'] = description;
      return FB.ui(fbObj);
    };

    /*
    	Shares the sublet on facebook
    */

    ShareManager.ShareSubletOnFB = function(marker, sublet, images) {
      var fbObj, primary_image, url;
      url = 'https://cribspot.com/listing/' + sublet.listing_id;
      $(document).trigger("track_event", ["Share", "Listing on FB", "Completed Sublet", sublet.listing_id]);
      primary_image = 'https://s3-us-west-2.amazonaws.com/cribspot-img/upright_logo.png';
      fbObj = {
        method: 'feed',
        link: url,
        picture: primary_image,
        name: "" + (marker.GetName()) + " - Check out my sublet on Cribspot!",
        caption: "I am subletting my place on Cribspot. Message me if you are interested.",
        description: sublet.description
      };
      return FB.ui(fbObj, function(response) {
        if (response != null ? response.post_id : void 0) {
          return $(document).trigger("track_event", ["Share", "Listing on FB Completed", "Completed Sublet", sublet.listing_id]);
        }
      });
    };

    /*
    	Shares the school page on facebook
    */

    ShareManager.ShareOnFacebook = function() {
      var fbObj;
      $(document).trigger("track_event", ["Share", "Website on FB", "Header Button"]);
      fbObj = {
        method: 'feed',
        link: "https://cribspot.com/",
        picture: 'https://s3-us-west-2.amazonaws.com/cribspot-img/upright_logo.png',
        name: "Join Cribspot",
        caption: "It's a party!",
        description: "Make your life easier...use Cribspot. Search off-campus houses and apartments quickly."
      };
      return FB.ui(fbObj, function(response) {
        if (response != null ? response.post_id : void 0) {
          return $(document).trigger("track_event", ["Share", "Website on FB Completed", "Header Button"]);
        }
      });
    };

    ShareManager.FBPromotion = function() {
      var fbObj;
      $(document).trigger("track_event", ["Share", "Website on FB", "Wisconsin Sunglasses"]);
      fbObj = {
        method: 'feed',
        link: "https://cribspot.com/",
        picture: 'https://lh4.googleusercontent.com/-JCwU1KBqw1I/UnAMzgSnPeI/AAAAAAAAAIA/ySQHQfwYGFA/w726-h545-no/sunglasses.jpg',
        name: "Free Shades for Wisconsin Students!",
        caption: "You're gonna need to protect your eyes - your off-campus housing search is now looking pretty bright.",
        description: "To celebrate our recent launch at University of Wisconsin-Madison, we're giving away 5 pairs of these awesome sunglasses! Offer only valid for Wisconsin students - just share this post to qualify! We'll notify the winners on Thursday, October 31st."
      };
      return FB.ui(fbObj, function(response) {
        if (response != null ? response.post_id : void 0) {
          return $(document).trigger("track_event", ["Share", "Website on FB Completed", "Wisconsin Sunglasses"]);
        }
      });
    };

    ShareManager.CopyListingUrl = function(listing_id, street_address, city, state, zip) {
      var url;
      url = this.GetShareUrl(listing_id, street_address, city, state, zip);
      return window.prompt("Copy to clipboard: Ctrl+C, Enter", url);
    };

    ShareManager.ShareSubletOnTwitter = function(listing_id) {
      var url, x, y;
      url = this.GetTwitterShareUrl(listing_id);
      $(document).trigger("track_event", ["Share", "Listing on Twitter", "Completed Sublet", listing_id]);
      x = screen.width / 2 - 600 / 2;
      y = screen.height / 2 - 350 / 2;
      return window.open(url, 'winname', "directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=350,top=" + y + ",left=" + x);
    };

    ShareManager.ShareListingOnTwitter = function(listing_id, street_address, city, state, zip) {
      var url, x, y;
      url = this.GetTwitterShareUrl(listing_id);
      $(document).trigger("track_event", ["Share", "Listing on Twitter", "", listing_id]);
      x = screen.width / 2 - 600 / 2;
      y = screen.height / 2 - 350 / 2;
      return window.open(url, 'winname', "directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=350,top=" + y + ",left=" + x);
    };

    ShareManager.GetTwitterShareUrl = function(listing_id) {
      var url;
      url = 'https://cribspot.com/listing/' + listing_id;
      return 'https://twitter.com/share?url=' + encodeURIComponent(url) + '&text=' + encodeURIComponent('Check out this listing on Cribspot!') + '&via=TheCribspot';
    };

    ShareManager.InitTweetButton = function(listing_id, street_address, city, state, zip) {
      var tweetBtn, url;
      if (listing_id === null || street_address === null || city === null || state === null || zip === null) {
        return null;
      }
      url = this.GetShareUrl(listing_id, street_address, city, state, zip);
      $('#twitterDiv iframe').remove();
      tweetBtn = $('<a></a>').addClass('twitter-share-button').attr('href', 'https://twitter.com/share').attr('data-url', url).attr('data-text', 'Check out this awesome property on Cribspot.com! ' + url).attr('data-via', 'TheCribspot');
      $('#twitterDiv').append(tweetBtn);
      return twttr.widgets.load();
    };

    ShareManager.EmailInvite = function(email_list) {
      return $.ajax({
        url: myBaseUrl + "Invitations/InviteFriends",
        type: 'POST',
        data: {
          emails: email_list
        }
      });
    };

    /*
    	Show Share Modal
    	Will show the email or fb modal dependent on whether
    */

    ShareManager.ShowShareModal = function(subject, message, type) {
      var _this = this;
      return typeof FB !== "undefined" && FB !== null ? FB.getLoginStatus(function(response) {
        if (response.status === 'unknown') {
          $(document).trigger("track_event", ["Share", "Invite Friends", "Email Invite"]);
          $("#email_invite").modal("show");
          $("#email_invite").find(".modal_subject").text(subject);
          $("#email_invite").find(".modal_message").text(message);
          return $("#send_email_invite").unbind("click").click(function(event) {
            var emails;
            $("#send_email_invite").button("loading");
            emails = [];
            $(".completed_roommate").find(".roommate_email").each(function(index, element) {
              return emails.push($(element).val());
            });
            _this.EmailInvite(emails).always(function() {
              $("#email_invite").modal("hide");
              return $("#send_email_invite").button("reset");
            });
            return $(document).trigger("track_event", ["Share", "Invite Friends Completed", "Email Invite", emails != null ? emails.length : void 0]);
          });
        } else {
          $(document).trigger("track_event", ["Share", "Invite Friends", "FB Invite"]);
          return FB.ui({
            method: 'apprequests',
            message: message
          }, function(response) {
            var _ref;
            return $(document).trigger("track_event", ["Share", "Invite Friends", "FB Invite", (_ref = response.to) != null ? _ref.length : void 0]);
          });
        }
      }) : void 0;
    };

    $("#header").ready(function() {
      $(".share_on_fb").click(function() {
        return ShareManager.ShareOnFacebook();
      });
      return $(".promotion_on_fb").click(function() {
        return ShareManager.FBPromotion();
      });
    });

    $("#email_invite").ready(function() {
      $("#email_invite").on("keyup", ".roommate_email", function(event) {
        var re;
        re = /\S+@\S+\.\S+/;
        if (re.test($(event.currentTarget.parentElement).find(".roommate_email").val())) {
          $(event.currentTarget.parentElement).addClass("completed_roommate");
          return;
        }
        return $(event.currentTarget.parentElement).removeClass("completed_roommate");
      });
      return $(".add_roommate").click(function() {
        var email_row, row_count;
        row_count = $("#email_invite").find(".roommate_email").last().data("roommate-count");
        email_row = $("<div class='roommate_row'><input data-roommate-count='" + (row_count + 1) + "' class='roommate_email' type='email' placeholder='E.g. myhousem@te.com'><i class='icon-ok-sign'></i></div>");
        return $("#email_invite").find(".email_invite_list").append(email_row);
      });
    });

    return ShareManager;

  }).call(this);

}).call(this);
