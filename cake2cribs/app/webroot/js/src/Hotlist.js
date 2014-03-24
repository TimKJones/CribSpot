(function() {

  (function($) {
    $.fn.removeStyle = function(style) {
      var search;
      search = new RegExp(style + '[^;]+;?', 'g');
      return this.each(function() {
        return $(this).attr('style', function(i, style) {
          if (style) return style.replace(search, '');
        });
      });
    };
    return $.fn.animateHighlight = function(highlightColor, duration) {
      var animateMs, highlightBg, originalBg, originalColor;
      highlightBg = highlightColor || "#FFFFFF";
      animateMs = duration || 500;
      originalBg = this.css("backgroundColor");
      originalColor = this.css('color');
      return this.stop().css("background-color", highlightBg).css('color', highlightBg).animate({
        backgroundColor: originalBg,
        color: originalColor
      }, animateMs);
    };
  })(jQuery);

  A2Cribs.Hotlist = (function() {

    Hotlist.Initialize = function() {
      var el;
      el = $('#hotlist');
      A2Cribs.HotlistObj = new A2Cribs.Hotlist(el);
      return A2Cribs.HotlistObj.setup();
    };

    Hotlist.prototype.call = function(action, method, data) {
      var deferred, url,
        _this = this;
      deferred = new $.Deferred();
      url = myBaseUrl + action;
      $.ajax({
        url: url,
        data: data,
        type: method,
        success: function(response) {
          try {
            return deferred.resolve(JSON.parse(response));
          } catch (error) {
            return deferred.reject(response);
          }
        },
        error: function(response) {
          return deferred.reject(response);
        }
      });
      return deferred.promise();
    };

    function Hotlist(DOMRoot) {
      this.DOMRoot = DOMRoot;
      this.topSection = _.template(A2Cribs.Hotlist.topSectionTemplate);
      this.friendsList = _.template(A2Cribs.Hotlist.friendsListTemplate);
      this.notLoggedIn = _.template(A2Cribs.Hotlist.notLoggedInTemplate);
      this.friendsListPopup = _.template(A2Cribs.Hotlist.friendsListPopupTemplate);
      this.expandButton = _.template(A2Cribs.Hotlist.expandButtonTemplate);
      this.sources = [
        {
          name: 'accounts',
          remote: {
            url: myBaseUrl + 'users/getbyname?name=%QUERY',
            filter: function(response) {
              return response.map(function(item) {
                return {
                  value: item.User.email,
                  name: "" + item.User.first_name + " " + item.User.last_name
                };
              });
            }
          }
        }
      ];
      this.setEditing(false);
      this.isExpanded = false;
    }

    Hotlist.prototype.handleFBLoad = function() {
      var _this = this;
      this.sources.push({
        name: 'facebook-friends',
        prefetch: {
          url: "https://graph.facebook.com/me/friends?access_token=" + (FB.getAccessToken()) + "&fields=id,name,picture,first_name,last_name",
          ttl: 0,
          filter: function(response) {
            return response.data.map(function(item) {
              return {
                value: item.name,
                tokens: item.name.split(' '),
                facebook_id: item.id,
                picture: item.picture.data.url,
                first_name: item.first_name,
                last_name: item.last_name
              };
            });
          }
        }
      });
      return this.DOMRoot.find('#add-field').typeahead(this.sources).on('typeahead:selected', function(e, d, ds) {
        return _this.setAddIdField(e, d, ds);
      }).on('typeahead:autocompleted', function(e, d, ds) {
        return _this.setAddIdField(e, d, ds);
      }).on('typeahead:hinted', function(e, d, ds) {
        return _this.setAddIdField(e, d, ds);
      }).bind('change cut paste keyup', function() {
        if ($(this).val() === '') return $(this).removeData('friend');
      });
    };

    Hotlist.prototype.setAddIdField = function(event, datum, dataset) {
      var name, val;
      name = datum.value.replace(/^\s+|\s+$/g, "").toLowerCase();
      val = $('#add-field').val().replace(/^\s+|\s+$/g, "").toLowerCase();
      if (name === val) {
        return this.DOMRoot.find('#add-field').data('friend', datum);
      } else {
        return this.DOMRoot.find('#add-field').removeData('friend');
      }
    };

    Hotlist.prototype.setup = function() {
      var _this = this;
      return $(document).on("checked_logged_in logged_in", function(event) {
        var logged_in, _ref;
        logged_in = (_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0;
        _this.renderTopSection(logged_in);
        _this.show();
        _this.renderBottomSection();
        _this.currentHotlist = _this.get();
        return _this.setHeight(true);
      });
    };

    Hotlist.prototype.setupDroppables = function() {
      this.DOMRoot.find('li.friend').droppable({
        accept: '.fl-sb-item, .large-bubble',
        hoverClass: 'drop-hover',
        tolerance: 'pointer',
        drop: function(event, ui) {
          var listing_id;
          listing_id = ui.draggable.attr('listing_id') || ui.draggable.data('listing_id');
          if ($(this).data('facebook_id')) {
            A2Cribs.HotlistObj.shareToFB(listing_id, $(this).data('facebook_id'));
          } else {
            A2Cribs.HotlistObj.shareToEmail(listing_id, $(this).data('email'));
          }
          $(this).find('.friend-abbr').animateHighlight();
          return ui.helper.hide();
        }
      });
      return this.DOMRoot.find('ul.friends.no-friends').droppable({
        accept: '.fl-sb-item, .large-bubble',
        hoverClass: 'drop-hover',
        tolerance: 'pointer',
        drop: function(event, ui) {
          var listing_id;
          listing_id = ui.draggable.attr('listing_id') || ui.draggable.data('listing_id');
          ui.helper.hide();
          return FB.ui({
            method: 'send',
            link: "http://www.cribspot.com/listing/" + listing_id,
            name: "Share this listing"
          }, function(response) {});
        }
      });
    };

    Hotlist.prototype.destroyDroppables = function() {
      this.DOMRoot.find('li.friend').droppable("destroy");
      return this.DOMRoot.find('ul.friends.no-friends').droppable("destroy");
    };

    Hotlist.prototype.renderTopSection = function(logged_in) {
      var _this = this;
      this.DOMRoot.find('#top-section').html(this.topSection({
        loggedIn: logged_in
      }));
      this.DOMRoot.find('#title').show();
      this.DOMRoot.find('#add-field').hide();
      this.DOMRoot.find('#btn-add').hide();
      $.when(window.fbInit).then(function() {
        return FB.getLoginStatus(function(response) {
          if (response.status === 'connected') return _this.handleFBLoad();
        });
      });
      this.DOMRoot.find('.twitter-typeahead').hide();
      this.DOMRoot.find('#link-info').popover({
        title: 'What is this?',
        content: "You can share listings with your friends!<br/>Either click the <i class='icon-user'></i> icon on a listing or drag the listing to one of your friends on the hotlist.",
        html: true,
        placement: 'bottom'
      });
      return $("#add-field").keyup(function(event) {
        if (event.keyCode === 13) return $("#btn-add").click();
      });
    };

    Hotlist.prototype.renderFriendsList = function(data) {
      if (A2Cribs.Login.logged_in) {
        this.DOMRoot.find('#friends').html(this.friendsList(data));
        this.DOMRoot.find('#add-field').val("");
        this.DOMRoot.find('.tt-hint').val("");
        this.DOMRoot.find('.btn-hotlist-remove').hide();
        this.DOMRoot.find('.friend-name').hide();
        $(document).on('mousedown mouseup', '.grab, .grabbing', function(event) {
          return $(this).toggleClass('grab').toggleClass('grabbing');
        });
        this.setupDroppables();
        this.DOMRoot.find('li.friend').tooltip({
          animated: 'fade',
          container: 'body'
        });
      } else {
        this.DOMRoot.find("#friends").html(this.notLoggedIn());
      }
      return this.setHeight(true);
    };

    Hotlist.prototype.startedDragging = function() {
      var _ref;
      if ((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0) {
        this.retract();
        return this.expand();
      }
    };

    Hotlist.prototype.stoppedDragging = function() {
      var _ref;
      if ((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0) {
        return this.retract();
      }
    };

    Hotlist.prototype.shareToAll = function(event, ui) {
      var fb_ids, listing_id;
      listing_id = ui.draggable.attr('listing_id') || ui.draggable.data('listing_id');
      return fb_ids = $('ul.friends li').map(function(i) {
        var a;
        a = $(this).data('facebook_id');
        return a;
      });
    };

    Hotlist.prototype.renderBottomSection = function() {
      return this.DOMRoot.find('#bottom-section').html(this.expandButton());
    };

    Hotlist.prototype.getHotlistForPopup = function(listing_id) {
      return this.friendsListPopup({
        friends: this.currentHotlist,
        listing_id: listing_id
      });
    };

    Hotlist.prototype.get = function() {
      var _this = this;
      if (A2Cribs.Login.logged_in) {
        return $.when(this.call('friends/hotlist', 'GET', null)).then(function(data) {
          return _this.currentHotlist = data;
        }).fail(function(data) {});
      }
    };

    Hotlist.prototype.show = function() {
      var _this = this;
      if (A2Cribs.Login.logged_in) {
        return $.when(this.call('friends/hotlist', 'GET', null)).then(function(data) {
          return _this.renderFriendsList({
            friends: data
          });
        }).fail(function(data) {});
      } else {
        return this.renderFriendsList(null);
      }
    };

    Hotlist.prototype.add = function(friend) {
      var postdata, route, _ref,
        _this = this;
      if (A2Cribs.Login.logged_in) {
        if (((_ref = $('#add-field').data('friend')) != null ? _ref.facebook_id : void 0) != null) {
          route = 'invitations/invitefbfriend';
          postdata = {
            friend: $('#add-field').data('friend')
          };
          this.showFBAddMessageModal($('#add-field').data('friend').facebook_id);
        } else {
          route = 'invitations/invitefriends';
          postdata = {
            emails: [$('#add-field').val()]
          };
        }
        return $.when(this.call(route, 'POST', postdata).then(function(data) {
          return _this.call('friends/hotlist', 'GET', null);
        })).then(function(data) {
          _this.currentHotlist = data;
          _this.renderFriendsList({
            friends: data
          });
          return _this.expandForEdit();
        }).fail(function(data) {});
      }
    };

    Hotlist.prototype.showFBAddMessageModal = function(friend) {
      return FB.ui({
        method: 'send',
        link: 'http://www.cribspot.com',
        to: friend
      });
    };

    Hotlist.prototype.remove = function(friend) {
      var _this = this;
      if (A2Cribs.Login.logged_in) {
        return $.when(this.call('friends/hotlist/remove', 'POST', {
          friend: friend
        }).then(function(data) {
          _this.renderFriendsList({
            friends: data
          });
          _this.expandForEdit();
          return _this.currentHotlist = data;
        })).fail(function(data) {});
      }
    };

    Hotlist.prototype.share = function(listing, friend) {
      var _this = this;
      if (A2Cribs.Login.logged_in) {
        return $.when(this.call('friends/share', 'POST', {
          friend: friend,
          listing: listing
        }).then(function(data) {
          if (data.success === true) {
            return A2Cribs.UIManager.Success("Successfully Shared Listing");
          } else {
            return A2Cribs.UIManager.Error("There was a problem sharing the listing.");
          }
        })).fail(function(data) {
          return A2Cribs.UIManager.Error("There was a problem sharing the listing.");
        });
      }
    };

    Hotlist.prototype.shareToEmail = function(listing, friend) {
      var _this = this;
      return $.when(this.call('invitations/inviteFriends', 'POST', {
        emails: [friend],
        listing: listing
      }).then(function(data) {
        if (data.success === true) {
          return A2Cribs.UIManager.Success("Successfully Shared Listing");
        } else {
          return A2Cribs.UIManager.Error("There was a problem sharing the listing.");
        }
      })).fail(function(data) {
        return A2Cribs.UIManager.Error("There was a problem sharing the listing.");
      }).always(function(data, status, jqXHR) {
        return $('#share-to-email').val("");
      });
    };

    Hotlist.prototype.shareToFB = function(listing, facebook_id) {
      return FB.ui({
        method: 'send',
        link: "http://www.cribspot.com/listing/" + listing,
        to: facebook_id,
        name: "Share this listing"
      });
    };

    Hotlist.prototype.retract = function() {
      var hides, shows;
      shows = ['.friend-abbr', '#title'];
      hides = ['.btn-hotlist-remove', '.friend-name', '#add-field', '.twitter-typeahead', '.tt-hint', '#btn-add'];
      this.DOMRoot.removeClass('expanded').removeClass('detailed');
      this.DOMRoot.find('#expand-button i').removeClass('icon-caret-up').addClass('icon-caret-down');
      this.DOMRoot.find(shows.join(',')).show();
      this.DOMRoot.find(hides.join(',')).hide();
      this.DOMRoot.find('#btn-edit').removeClass('editing').html('<i class="icon-edit"></i>');
      this.DOMRoot.find('ul.friends').removeStyle('height');
      this.setEditing(false);
      this.isExpanded = false;
      this.setHeight(true);
      this.setupDroppables();
      return this.DOMRoot.find('li.friend').tooltip({
        animated: 'fade',
        container: 'body'
      });
    };

    Hotlist.prototype.expand = function() {
      this.DOMRoot.addClass('expanded');
      this.DOMRoot.find('#expand-button i').removeClass('icon-caret-down').addClass('icon-caret-up');
      this.isExpanded = true;
      return this.setHeight();
    };

    Hotlist.prototype.expandForEdit = function() {
      var hides, shows;
      this.DOMRoot.addClass('expanded');
      this.DOMRoot.find('#expand-button i').removeClass('icon-caret-down').addClass('icon-caret-up');
      this.isExpanded = true;
      this.DOMRoot.addClass('detailed');
      shows = ['.btn-hotlist-remove', '.twitter-typeahead', '.tt-hint', '.friend-name', '#add-field', '#btn-add'];
      hides = ['.friend-abbr', '#title'];
      this.DOMRoot.find(shows.join(',')).show();
      this.DOMRoot.find(hides.join(',')).hide();
      this.DOMRoot.find('#btn-edit').addClass('editing').html('Done');
      this.DOMRoot.find('li.friend').tooltip("destroy");
      this.destroyDroppables();
      return this.setHeight(false, true);
    };

    Hotlist.prototype.showOrHideExpandArrow = function() {
      var el, hotlistOnOneLine, _ref;
      el = this.DOMRoot.find('#bottom-section a');
      if (this.DOMRoot.find('ul.friends li').length) {
        hotlistOnOneLine = this.DOMRoot.find('ul.friends li:first').offset().top === this.DOMRoot.find('ul.friends li:last').offset().top;
      } else {
        hotlistOnOneLine = true;
      }
      if (!((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0)) {
        el.hide();
        return;
      }
      if (this.isExpanded || !hotlistOnOneLine) {
        return el.show();
      } else {
        return el.hide();
      }
    };

    Hotlist.prototype.setHeight = function(retract, max) {
      var a, height, _ref,
        _this = this;
      if (retract == null) retract = false;
      if (max == null) max = false;
      this.showOrHideExpandArrow();
      if (retract) {
        a = this.DOMRoot.find('ul.friends li:first-child');
      } else {
        a = this.DOMRoot.find('ul.friends li:last-child');
      }
      if (a.length) {
        height = a.offset().top + a.height() - $('ul.friends').offset().top;
      } else {
        height = 0;
      }
      if (height <= 10) height = 70;
      if ($('#bottom-section a').is(":visible")) {
        height = height + $('#bottom-section a').height() + 20;
      }
      if (!((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0)) {
        height = height + 25;
      }
      if (height < 300 || !max) {
        this.DOMRoot.find('ul.friends').height(height);
      } else {
        this.DOMRoot.find('ul.friends').height(300);
      }
      return this.DOMRoot.find('ul.friends').on('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function() {
        return A2Cribs.FeaturedListings.resizeHandler();
      });
    };

    Hotlist.prototype.toggleEdit = function() {
      if (this.isEditing()) {
        this.setEditing(false);
        return this.retract();
      } else {
        this.setEditing(true);
        return this.expandForEdit();
      }
    };

    Hotlist.prototype.toggleExpand = function() {
      if ($('#hotlist').hasClass('expanded')) {
        return this.retract();
      } else {
        return this.expand(false);
      }
    };

    Hotlist.prototype.isEditing = function() {
      return this.DOMRoot.hasClass('editing');
    };

    Hotlist.prototype.setEditing = function(state) {
      if (state) {
        return this.DOMRoot.addClass('editing');
      } else {
        return this.DOMRoot.removeClass('editing');
      }
    };

    Hotlist.friendsListPopupTemplate = "<div id='shareto'>\n  <input type='email' id='share-to-email' placeholder='to email'></input>\n  <a class='share-to-email-btn' href='#' onClick='A2Cribs.HotlistObj.shareToEmail(<%=listing_id%>, $(\"#share-to-email\").val());'>\n    <i class=\"icon-share\"></i>\n  </a>\n</div>\n<ul class=\"friends-popup\">\n  <% _.each(friends, function(elem, idx, list) { %>\n    <li>\n      <% name = elem.first_name ? elem.first_name + ' ' + elem.last_name : elem.email %>\n      <% if(elem.facebook_id) { %>\n        <a href='#' onclick='A2Cribs.HotlistObj.shareToFB(<%=listing_id%>, <%=elem.facebook_id%>)'><%=name%></a>\n      <% } else { %>\n        <a href='#' onclick='A2Cribs.HotlistObj.share(<%=listing_id%>, <%=elem.id%>)'><%=name%></a>\n      <% } %>\n    </li>\n  <% }) %>\n</ul>";

    Hotlist.topSectionTemplate = "<div id='share-all'>\n  <span class='title'>Share with your Friends <a title='What is this?' href='#' id='link-info' class='icon icon-info-sign'></a></span>\n  <span class='share-text'>Share to All</span>\n</div>\n<input class='typeahead' type='text' autocomplete='off' id='add-field'></input>\n<div id='buttons' class='pull-right <%=loggedIn ? \"\" : \"hide\"%>'>\n  <a href='#' data-toggle='popover' id='btn-add' class='btn-hotlist btn-hotlist-add' onClick=\"A2Cribs.HotlistObj.add($('#add-field').val())\">+</a>\n  <a href='#' id='btn-edit' class='btn-hotlist btn-hotlist-edit' onClick='A2Cribs.HotlistObj.toggleEdit()'><i class='icon-edit'></i></a>\n</div>\n<div style='clear: both;'></div>";

    Hotlist.friendsListTemplate = "<ul class='friends <%=friends.length ? \"has-friends\" : \"no-friends\"%>'>\n  <% if(friends.length) { %>\n  <% _.each(friends, function(elem, idx, list) { %>\n    <% \n      var tooltitle = elem.email \n      if (elem.first_name) {\n        tooltitle = elem.first_name + ' ' + elem.last_name\n      }\n    %> \n    <li class='friend' data-id='<%=elem.id%>' data-toggle='tooltip' title='<%=tooltitle%>'' data-facebook_id='<%=elem.facebook_id || null%>' data-email='<%=elem.email%>'>\n      <% if (elem.facebook_id){ %>\n        <img class='friend-abbr hotlist-profile-img' src='https://graph.facebook.com/<%=elem.facebook_id%>/picture?width=80&height=80'></img>\n      <% } else if (elem.profile_img) { %>\n        <img class='friend-abbr otlist-profile-img' src='<%=elem.profile_img%>'></img>\n      <% } else if (typeof elem.first_name !== 'undefined' && elem.first_name !== null) { %>\n        <span class='friend-abbr'>\n          <%=elem.first_name[0].toUpperCase()%><%=elem.last_name[0].toUpperCase()%> \n        </span>\n      <% } else { %>\n        <span class='friend-abbr'>\n          <%=elem.email[0]%>@<%=elem.email.split('@')[1][0]%>\n        </span>\n      <% } %>\n      <span class='friend-name'>\n        <% if (typeof elem.first_name !== 'undefined' && elem.first_name !== null) { %>\n          <%=elem.first_name%> <%=elem.last_name%> \n        <% } else { %>\n          <%=elem.email%>\n        <% } %>\n      </span>\n      <a class='btn-hotlist-remove btn-hotlist pull-right' href='#' onClick='A2Cribs.HotlistObj.remove(<%=elem.id%>)'><i class='icon icon-remove-circle'></i></a>\n    </li>\n  <% }); %>\n  <% } else { %>\n    <li class='add-friends-notice'>No friends added yet.</li>\n    <li class='no-friends-notice'>Add friends by clicking here <i class='icon-reply icon-rotate'></i></li>\n    <li class='share-to-fb-notice'><i class='icon-facebook-sign'></i> Drag to Share</li>\n  <% } %>\n</ul>";

    Hotlist.notLoggedInTemplate = "<ul class='friends no-friends not-logged-in'>\n  <li class='not-logged-in-notice'>Log In to share</li>\n</ul>";

    Hotlist.expandButtonTemplate = "<a href='#' onclick='A2Cribs.HotlistObj.toggleExpand()' id='expand-button'><i class='icon icon-caret-down'></i></a>";

    return Hotlist;

  })();

}).call(this);
