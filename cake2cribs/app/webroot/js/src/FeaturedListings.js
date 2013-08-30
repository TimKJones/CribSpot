(function() {

  A2Cribs.FeaturedListings = (function() {
    var Sidebar;

    function FeaturedListings() {}

    FeaturedListings.GetFlIds = function(university_id) {
      var deferred,
        _this = this;
      deferred = new $.Deferred();
      $.get("/featuredListings/cycleIds/" + university_id + "/" + this.FL_LIMIT, function(response) {
        var listing_ids;
        listing_ids = JSON.parse(response);
        if (listing_ids != null) {
          return deferred.resolve(listing_ids);
        } else {
          return deferred.resolve(null);
        }
      });
      return deferred.promise();
    };

    FeaturedListings.FL_LIMIT = 5;

    FeaturedListings.GetListingDeferred = function(id, type) {
      var deferred, listing_id, listing_type,
        _this = this;
      deferred = new $.Deferred();
      listing_id = id;
      listing_type = type;
      $.ajax({
        url: myBaseUrl + "Listings/GetListing/" + listing_id,
        type: "GET",
        success: function(data) {
          var item, key, listing, response_data, value, _i, _len;
          response_data = JSON.parse(data);
          for (_i = 0, _len = response_data.length; _i < _len; _i++) {
            item = response_data[_i];
            for (key in item) {
              value = item[key];
              if (A2Cribs[key] != null) {
                A2Cribs.UserCache.Set(new A2Cribs[key](value));
              }
            }
          }
          listing = A2Cribs.UserCache.Get(listing_type, listing_id);
          return deferred.resolve(item);
        },
        error: function() {
          return deferred.resolve(null);
        }
      });
      return deferred.promise();
    };

    FeaturedListings.FetchListingsByIds = function(listing_ids, active_listing_type) {
      var deferred, id, listingDefereds, _i, _len,
        _this = this;
      deferred = new $.Deferred();
      if (!listing_ids || listing_ids.length < 1) {
        deferred.resolve(null);
        return deferred;
      }
      listingDefereds = [];
      for (_i = 0, _len = listing_ids.length; _i < _len; _i++) {
        id = listing_ids[_i];
        listingDefereds.push(A2Cribs.FeaturedListings.GetListingDeferred(id, active_listing_type));
      }
      $.when.apply($, listingDefereds).then(function() {
        return deferred.resolve(arguments);
      });
      return deferred.promise();
    };

    FeaturedListings.GetRandomListingsFromMap = function(num_) {
      var num,
        _this = this;
      if (!(this.RanListingsDeferred != null)) {
        this.RanListingsDeferred = new $.Deferred();
      }
      num = num_;
      $.when(A2Cribs.Map.LoadBasicData()).then(function(data) {
        var basic_data, shuf, sliced;
        basic_data = JSON.parse(data);
        shuf = _.shuffle(basic_data);
        sliced = shuf.slice(0, num);
        return _this.RanListingsDeferred.resolve(sliced);
      });
      return this.RanListingsDeferred.promise();
    };

    FeaturedListings.InitializeSidebar = function(university_id, active_listing_type) {
      var NUM_RANDOM_LISTINGS, alt, sidebar,
        _this = this;
      alt = active_listing_type;
      if (!(this.SidebarListingCache != null)) this.SidebarListingCache = {};
      if (!(this.FLListingIds != null)) this.FLListingIds = [];
      NUM_RANDOM_LISTINGS = 35;
      sidebar = new Sidebar($('#fl-side-bar'));
      this.GetFlIds(university_id).done(function(ids) {
        var id, _i, _len;
        if (ids === null) return;
        for (_i = 0, _len = ids.length; _i < _len; _i++) {
          id = ids[_i];
          _this.FLListingIds.push(parseInt(id));
        }
        return _this.FetchListingsByIds(ids, alt).done(function(listings) {
          return sidebar.addListings(listings, 'featured');
        });
      });
      return $.when(this.GetRandomListingsFromMap(NUM_RANDOM_LISTINGS)).then(function(listings) {
        var listing, _i, _len;
        if (listings === null) return;
        sidebar.addListings(listings, 'ran');
        for (_i = 0, _len = listings.length; _i < _len; _i++) {
          listing = listings[_i];
          if (listing.Listing != null) {
            A2Cribs.FavoritesManager.setFavoriteButton(listing.Listing.listing_id.toString(), null, A2Cribs.FavoritesManager.FavoritesListingIds);
          }
        }
        return $(".fl-sb-item").click(function(event) {
          var listing_id, marker, markerPosition, marker_id;
          marker_id = parseInt($(event.currentTarget).attr('marker_id'));
          listing_id = parseInt($(event.currentTarget).attr('listing_id'));
          marker = A2Cribs.UserCache.Get('marker', marker_id);
          listing = A2Cribs.UserCache.Get('listing', listing_id);
          A2Cribs.Map.GMap.setZoom(16);
          A2Cribs.HoverBubble.Open(marker);
          A2Cribs.MixPanel.Click(listing, 'sidebar listing');
          markerPosition = marker.GMarker.getPosition();
          return A2Cribs.Map.CenterMap(markerPosition.lat(), markerPosition.lng());
        });
      });
    };

    Sidebar = (function() {

      function Sidebar(SidebarUI) {
        this.SidebarUI = SidebarUI;
        this.ListItemTemplate = _.template(A2Cribs.FeaturedListings.ListItemHTML);
      }

      Sidebar.prototype.addListings = function(listings, list, clear) {
        var list_html;
        if (clear == null) clear = true;
        if (listings === null) return;
        list_html = this.getListHtml(listings);
        if (clear) {
          return this.SidebarUI.find("#" + list + "-listings").html(list_html);
        } else {
          return this.SidebarUI.find("#" + list + "-listings").append(list_html);
        }
      };

      Sidebar.prototype.getDateString = function(date) {
        var month, year;
        if (!(this.MonthArray != null)) {
          this.MonthArray = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        }
        month = this.MonthArray[date.getMonth()];
        year = date.getFullYear();
        return "" + month + " " + year;
      };

      Sidebar.prototype.getListHtml = function(listings) {
        var beds, data, image, lease_length, list, listing, name, primary_image_path, rent, start_date, _i, _j, _len, _len2, _ref;
        list = "";
        for (_i = 0, _len = listings.length; _i < _len; _i++) {
          listing = listings[_i];
          rent = name = beds = lease_length = start_date = null;
          if (listing.Rental.rent != null) {
            rent = parseFloat(listing.Rental.rent).toFixed(0);
          } else {
            rent = ' --';
          }
          if (listing.Marker.alternate_name != null) {
            name = listing.Marker.alternate_name;
          } else {
            name = listing.Marker.street_address;
          }
          if (listing.Rental.lease_length != null) {
            lease_length = listing.Rental.lease_length;
          } else {
            lease_length = '-- ';
          }
          if (listing.Rental.beds > 1) {
            beds = "" + listing.Rental.beds + " beds";
          } else {
            beds = "" + listing.Rental.beds + " bed";
          }
          if (listing.Rental.start_date != null) {
            start_date = this.getDateString(new Date(listing.Rental.start_date));
          } else {
            start_date = 'Start Date --';
          }
          if (start_date === 'Dec 1969') alert('stop');
          primary_image_path = '/img/sidebar/no_photo_small.jpg';
          if (listing.Image != null) {
            _ref = listing.Image;
            for (_j = 0, _len2 = _ref.length; _j < _len2; _j++) {
              image = _ref[_j];
              if (image.is_primary) primary_image_path = '/' + image.image_path;
            }
          }
          data = {
            rent: rent,
            beds: beds,
            building_type: listing.Marker.building_type_id,
            start_date: start_date,
            lease_length: lease_length,
            name: name,
            img: primary_image_path,
            listing_id: listing.Listing.listing_id,
            marker_id: listing.Marker.marker_id
          };
          list += this.ListItemTemplate(data);
        }
        return list;
      };

      return Sidebar;

    })();

    FeaturedListings.ListItemHTML = "<div class = 'fl-sb-item' listing_id=<%= listing_id %> marker_id=<%= marker_id %>>\n    <span class = 'img-wrapper'>\n        <img src = '<%=img%>'></img>\n    </span>\n    <span class = 'vert-line'></span>\n    <span class = 'info-wrapper'>\n        <div class = 'info-row'>\n            <span class = 'rent price-text'><%= \"$\" + rent %></span>\n            <span class = 'divider'>|</span>\n            <span class = 'beds'><%= beds %> </span>\n            <span class = 'favorite pull-right'><i class = 'icon-heart fav-icon share_btn favorite_listing' id='<%= listing_id %>'></i></span>    \n        </div>\n        <div class = 'row-div'></div>\n        <div class = 'info-row'>\n            <span class = 'building-type'><%= building_type %></span>\n            <span class = 'divider'>|</span>\n            <span class = 'lease-start'><%= start_date %></span> | <span class = 'lease_length'><%= lease_length %> months</span>\n        </div>\n        <div class = 'row-div'></div>\n        <div class = 'info-row'>\n            <i class = 'icon-map-marker'></i><span class = 'name'><%=name%></span>\n        </div>\n    </span>   \n</div>";

    return FeaturedListings;

  })();

}).call(this);
