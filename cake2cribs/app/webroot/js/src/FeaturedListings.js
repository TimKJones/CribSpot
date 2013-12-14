// Generated by CoffeeScript 1.6.3
(function() {
  A2Cribs.FeaturedListings = (function() {
    var Sidebar;

    function FeaturedListings() {}

    FeaturedListings.FeaturedPMIdToListingIdsMap = [];

    FeaturedListings.FeaturedPMListingsVisible = false;

    FeaturedListings.resizeHandler = function() {
      var h;
      h = $(window).height() - $('#listings-list').offset().top - $('.legal-bar').height();
      console.log($(window).height(), $('#listings-list').offset().top, $('.legal-bar').height(), h);
      return $('#listings-list').height(h);
    };

    FeaturedListings.SetupResizing = function() {
      this.resizeHandler();
      return $(window).on('resize', this.resizeHandler);
    };

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

    FeaturedListings.GetRandomListingsFromMap = function(num, all_listing_ids) {
      var shuf, sliced;
      shuf = _.shuffle(all_listing_ids);
      sliced = shuf.slice(0, num);
      return sliced;
    };

    FeaturedListings.InitializeSidebar = function(university_id, active_listing_type, basicDataDeferred, basicDataCachedDeferred) {
      var NUM_RANDOM_LISTINGS, alt, getFlIdsDeferred, sidebar,
        _this = this;
      alt = active_listing_type;
      if (this.SidebarListingCache == null) {
        this.SidebarListingCache = {};
      }
      if (this.FLListingIds == null) {
        this.FLListingIds = [];
      }
      NUM_RANDOM_LISTINGS = 25;
      sidebar = new Sidebar($('#fl-side-bar'));
      getFlIdsDeferred = this.GetFlIds(university_id);
      this.GetSidebarImagePathsDeferred = new $.Deferred();
      this.SetupResizing();
      $.when(getFlIdsDeferred, basicDataCachedDeferred).then(function(flIds) {
        var all_listing_ids, id, listing, listingObject, listing_object, listings, marker, randomIds, sidebar_listing_ids, _i, _j, _k, _l, _len, _len1, _len2, _len3;
        listings = A2Cribs.UserCache.Get('listing');
        all_listing_ids = [];
        for (_i = 0, _len = listings.length; _i < _len; _i++) {
          listing = listings[_i];
          if ((listing != null) && listing.listing_id) {
            all_listing_ids.push(parseInt(listing.listing_id));
          }
        }
        randomIds = null;
        if (all_listing_ids.length > 0) {
          randomIds = _this.GetRandomListingsFromMap(NUM_RANDOM_LISTINGS, all_listing_ids);
        }
        if ((flIds == null) && (randomIds == null)) {
          return;
        }
        sidebar_listing_ids = [];
        for (_j = 0, _len1 = flIds.length; _j < _len1; _j++) {
          id = flIds[_j];
          id = parseInt(id);
          _this.FLListingIds.push(id);
          sidebar_listing_ids.push(id);
        }
        if (randomIds != null) {
          for (_k = 0, _len2 = randomIds.length; _k < _len2; _k++) {
            id = randomIds[_k];
            sidebar_listing_ids.push(id);
          }
        }
        listings = [];
        for (_l = 0, _len3 = sidebar_listing_ids.length; _l < _len3; _l++) {
          id = sidebar_listing_ids[_l];
          listingObject = {};
          listing = A2Cribs.UserCache.Get('listing', id);
          marker = listing_object = null;
          if (listing != null) {
            listing.InSidebar(true);
            marker = A2Cribs.UserCache.Get('marker', listing.marker_id);
            listing_object = A2Cribs.UserCache.Get(A2Cribs.Map.ACTIVE_LISTING_TYPE, id);
            if (listing_object[0] != null) {
              listing_object = listing_object[0];
            }
          }
          if ((listing != null) && (marker != null) && (listing_object != null)) {
            listingObject.Listing = listing;
            listingObject.Marker = marker;
            listingObject.ListingObject = listing_object;
            listings.push(listingObject);
          } else {
            console.log(listing);
            console.log(marker);
            console.log(listing_object);
          }
        }
        sidebar.addListings(listings, 'ran');
        _this.GetSidebarImagePaths(sidebar_listing_ids);
        return $(".fl-sb-item").click(function(event) {
          var listing_id, markerPosition, marker_id;
          marker_id = parseInt($(event.currentTarget).attr('marker_id'));
          listing_id = parseInt($(event.currentTarget).attr('listing_id'));
          marker = A2Cribs.UserCache.Get('marker', marker_id);
          listing = A2Cribs.UserCache.Get('listing', listing_id);
          A2Cribs.Map.GMap.setZoom(16);
          $("#map_region").trigger("marker_clicked", [marker]);
          A2Cribs.MixPanel.Click(listing, 'sidebar listing');
          markerPosition = marker.GMarker.getPosition();
          return A2Cribs.Map.CenterMap(markerPosition.lat(), markerPosition.lng());
        }).draggable({
          revert: true,
          opacity: 0.7,
          cursorAt: {
            top: -12,
            right: -20
          },
          helper: function(event) {
            var name;
            name = $(this).find('.name').html() || "this listing";
            return $("<div class='listing-drag-helper'>Share " + name + "</div>");
          },
          start: function(event) {
            console.log('start');
            $('ul.friends, #hotlist').addClass('dragging');
            return A2Cribs.HotlistObj.startedDragging();
          },
          stop: function(event) {
            $('ul.friends, #hotlist').removeClass('dragging');
            return A2Cribs.HotlistObj.stoppedDragging();
          },
          appendTo: 'body'
        });
      });
      return $.when(this.GetSidebarImagePathsDeferred).then(function(images) {
        var image, img_element, _i, _len, _results;
        images = JSON.parse(images);
        _results = [];
        for (_i = 0, _len = images.length; _i < _len; _i++) {
          image = images[_i];
          if ((image != null) && (image.Image != null)) {
            img_element = $("#sb-img" + image.Image.listing_id);
            _results.push(img_element.attr('src', '/' + image.Image.image_path));
          } else {
            _results.push(void 0);
          }
        }
        return _results;
      });
    };

    FeaturedListings.GetSidebarImagePaths = function(listing_ids) {
      return $.ajax({
        url: myBaseUrl + "Images/GetPrimaryImages/" + JSON.stringify(listing_ids),
        type: "GET",
        success: function(data) {
          return FeaturedListings.GetSidebarImagePathsDeferred.resolve(data);
        },
        error: function() {
          return FeaturedListings.GetSidebarImagePathsDeferred.resolve(null);
        }
      });
    };

    FeaturedListings.LoadFeaturedPMListings = function() {
      return $.ajax({
        url: myBaseUrl + "Listings/GetFeaturedPMListings/" + A2Cribs.Map.CurentSchoolId,
        type: "GET",
        success: function(data) {
          FeaturedListings.FeaturedPMIdToListingIdsMap = JSON.parse(data);
          return $(".featured_pm").click(function(event) {
            var listing_ids, user_id;
            user_id = $(event.delegateTarget).data("user-id");
            if (FeaturedListings.FeaturedPMIdToListingIdsMap[user_id] != null) {
              listing_ids = FeaturedListings.FeaturedPMIdToListingIdsMap[user_id];
              if (A2Cribs.Map.ToggleListingVisibility(listing_ids, "PM_" + user_id)) {
                return A2Cribs.Map.IsCluster(true);
              } else {
                A2Cribs.Map.IsCluster(false);
                $(event.delegateTarget).addClass("active");
                return A2Cribs.MixPanel.Event('Sidebar Featured PM', {
                  pm_id: user_id
                });
              }
            }
          });
        },
        error: function() {
          return FeaturedListings.FeaturedPMIdToListingIdsMap = [];
        }
      });
    };

    Sidebar = (function() {
      function Sidebar(SidebarUI) {
        this.SidebarUI = SidebarUI;
        this.ListItemTemplate = _.template(A2Cribs.FeaturedListings.ListItemHTML);
      }

      Sidebar.prototype.addListings = function(listings, list, clear) {
        var list_html;
        if (clear == null) {
          clear = true;
        }
        if (listings === null) {
          return;
        }
        list_html = this.getListHtml(listings);
        if (clear) {
          return this.SidebarUI.find("#" + list + "-listings").append(list_html);
        } else {
          return this.SidebarUI.find("#" + list + "-listings").append(list_html);
        }
      };

      Sidebar.prototype.getDateString = function(date) {
        var month, year;
        if (this.MonthArray == null) {
          this.MonthArray = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        }
        month = this.MonthArray[date.getMonth()];
        year = date.getFullYear();
        return "" + month + " " + year;
      };

      Sidebar.prototype.getListHtml = function(listings) {
        var beds, data, end_date, image, lease_length, list, listing, listing_item, name, primary_image_path, rent, start_date, _i, _j, _len, _len1, _ref, _results;
        list = $("<div />");
        _results = [];
        for (_i = 0, _len = listings.length; _i < _len; _i++) {
          listing = listings[_i];
          rent = name = beds = lease_length = start_date = null;
          if (listing.ListingObject.rent != null) {
            rent = parseFloat(listing.ListingObject.rent).toFixed(0);
          } else {
            rent = ' --';
          }
          if (listing.Marker.alternate_name != null) {
            name = listing.Marker.alternate_name;
          } else {
            name = listing.Marker.street_address;
          }
          if (listing.ListingObject.lease_length != null) {
            lease_length = listing.ListingObject.lease_length;
          } else {
            lease_length = '-- ';
          }
          if (listing.ListingObject.beds > 1) {
            beds = "" + listing.ListingObject.beds + " beds";
          } else if (listing.ListingObject.beds != null) {
            beds = "" + listing.ListingObject.beds + " bed";
          } else {
            beds = "?? beds";
          }
          if (listing.ListingObject.start_date != null) {
            start_date = listing.ListingObject.start_date.toString().replace(' ', 'T');
            start_date = this.getDateString(new Date(start_date));
          } else {
            start_date = 'Start Date --';
          }
          if (listing.ListingObject.end_date != null) {
            end_date = listing.ListingObject.end_date.toString().replace(' ', 'T');
            end_date = this.getDateString(new Date(end_date));
          }
          primary_image_path = '/img/sidebar/no_photo_small.jpg';
          if (listing.Image != null) {
            _ref = listing.Image;
            for (_j = 0, _len1 = _ref.length; _j < _len1; _j++) {
              image = _ref[_j];
              if (image.is_primary) {
                primary_image_path = '/' + image.image_path;
              }
            }
          }
          data = {
            rent: rent,
            beds: beds,
            building_type: listing.Marker.building_type_id,
            start_date: start_date,
            end_date: end_date,
            lease_length: lease_length,
            name: name,
            img: primary_image_path,
            listing_id: listing.Listing.listing_id,
            marker_id: listing.Marker.marker_id
          };
          listing_item = $(this.ListItemTemplate(data));
          A2Cribs.FavoritesManager.setFavoriteButton(listing_item.find(".favorite"), listing.Listing.listing_id, A2Cribs.FavoritesManager.FavoritesListingIds);
          listing_item.find(".hotlist_share a").popover({
            content: function() {
              return A2Cribs.HotlistObj.getHotlistForPopup($(this).data('listing'));
            },
            html: true,
            trigger: 'manual',
            placement: 'left',
            container: 'body',
            title: 'Share this listing'
          }).click(function(e) {
            var _this = this;
            e.preventDefault();
            console.log('listing_item share click!');
            $(this).popover('show');
            return $('.popover a').on('click', function() {
              $('.popover').popover('hide');
              return $('.popover').off('click');
            });
          });
          listing_item.find("#share-to-email").keyup(function(event) {
            if (event.keyCode === 13) {
              return $(".share-to-email-btn").click();
            }
          });
          _results.push(list.append(listing_item));
        }
        return _results;
      };

      return Sidebar;

    })();

    FeaturedListings.ListItemHTML = "<div id = 'fl-sb-item-<%= listing_id %>' class = 'fl-sb-item' listing_id=<%= listing_id %> marker_id=<%= marker_id %>>\n    <span class = 'img-wrapper'>\n        <img id='sb-img<%=listing_id %>' src = '<%=img%>'></img>\n    </span>\n    <span class = 'vert-line'></span>\n    <span class = 'info-wrapper'>\n        <div class = 'info-row'>\n            <span class = 'rent price-text'><%= \"$\" + rent %></span>\n            <span class = 'divider'>|</span>\n            <span class = 'beds'><%= beds %> </span>\n            <span class = 'favorite pull-right'><i class = 'icon-heart fav-icon share_btn favorite_listing' id='<%= listing_id %>' data-listing-id='<%= listing_id %>'></i></span>    \n            <span class = 'hotlist_share pull-right'><a href='#' data-listing=\"<%=listing_id%>\"><i class='fav-icon icon-user'></i></a></span>\n            <span class = 'hotlist-share-grab grab pull-right'><i class='icon-reorder'></i><i class='icon-reorder'></i><i class=\"icon-reorder\"></i></span>\n        </div>\n        <div class = 'row-div'></div>\n        <div class = 'info-row'>\n            <span class = 'building-type'><%= building_type %></span>\n            <span class = 'divider'>|</span>\n            <% if (typeof(end_date) != \"undefined\") { %>\n            <span class = 'lease-start'><%= start_date %></span> - <span class = 'lease_length'><%= end_date %></span>\n            <% } else { %>\n            <span class = 'lease-start'><%= start_date %></span> | <span class = 'lease_length'><%= lease_length %> months</span>\n            <% } %>\n        </div>\n        <div class = 'row-div'></div>\n        <div class = 'info-row'>\n            <i class = 'icon-map-marker'></i><span class = 'name'><%=name%></span>\n        </div>\n    </span>   \n</div>";

    return FeaturedListings;

  }).call(this);

}).call(this);
