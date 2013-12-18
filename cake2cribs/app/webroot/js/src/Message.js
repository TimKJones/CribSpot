// Generated by CoffeeScript 1.6.3
(function() {
  A2Cribs.Messages = (function() {
    var create_message_div;

    function Messages() {}

    Messages.setupUI = function() {
      var _this = this;
      $('#send_reply').click(function() {
        return _this.sendReply();
      });
      $('#view_unread_cb').change(function() {
        return _this.toggleUnreadConversations();
      });
      $('#refresh_content').click(function() {
        return _this.refresh();
      });
      $('#current_conversation').scroll(function(event) {
        return _this.MessageScrollingHandler(event);
      });
      $('#meaning').click(function() {
        return $('#hidden-meaning').fadeToggle();
      });
      $('#delete_conversation').click(function() {
        return _this.DeleteConversation();
      });
      return this.refresh();
    };

    Messages.ScrollMessagesTo = function(mli) {
      var cc, dist;
      cc = $('#current_conversation');
      dist = (cc.offset().top + cc.innerHeight()) - (mli.offset().top + mli.innerHeight() + 10);
      return cc.scrollTop(cc.scrollTop() - dist);
    };

    Messages.MessageScrollingHandler = function(event) {
      if ($("#current_conversation").scrollTop() > 20 || this.NumMessagePages === 0) {
        return;
      }
      return this.loadMessages(this.NumMessagePages + 1);
    };

    Messages.refresh = function() {
      this.refreshUnreadCount();
      this.refreshConversations();
      if (this.CurrentConversation !== -1) {
        this.refreshParticipantInfo();
        return this.refreshMessages();
      }
    };

    Messages.refreshUnreadCount = function() {
      var url,
        _this = this;
      url = myBaseUrl + "messages/getUnreadCount";
      return $.get(url, function(data) {
        var response_data;
        response_data = JSON.parse(data);
        return $('#message_count').html(response_data.unread_messages);
      });
    };

    Messages.refreshConversations = function() {
      var url,
        _this = this;
      url = myBaseUrl + "messages/getConversations";
      return $.get(url, function(data) {
        var conversations, convo, list_item, message_count_box, _i, _len, _results;
        $("#messages_list_content").empty();
        conversations = JSON.parse(data);
        _results = [];
        for (_i = 0, _len = conversations.length; _i < _len; _i++) {
          convo = conversations[_i];
          message_count_box = $("<div />", {
            "class": "notification_count pull-right",
            text: convo.Conversation.unread_message_count
          });
          list_item = $("<li />", {
            text: convo.Conversation.title,
            "class": "messages_list_item",
            id: convo.Conversation.conversation_id,
            "data-participant": convo.Participant.id,
            "data-listing": convo.Conversation.listing_id,
            "data-title": convo.Conversation.title
          }).append(message_count_box);
          if (parseInt(convo.Conversation.unread_message_count, 10) > 0) {
            list_item.addClass("unread");
          }
          $("#messages_list_content").append(list_item);
          _results.push(_this.attachConversationListItemHandler(list_item));
        }
        return _results;
      });
    };

    Messages.toggleUnreadConversations = function() {
      this.ViewOnlyUnread = $('#view_unread_cb').is(':checked');
      return this.refreshConversations();
    };

    Messages.refreshParticipantInfo = function() {
      var conversation_id, participantid, url;
      participantid = Messages.CurrentParticipantID;
      conversation_id = Messages.CurrentConversation;
      if (Messages.ParticipantInfoCache[participantid] != null) {
        Messages.setParticipantInfoUI(Messages.ParticipantInfoCache[participantid]);
        return;
      }
      url = url = myBaseUrl + "messages/getParticipantInfo/" + conversation_id + "/";
      return $.ajax({
        url: url,
        type: "GET",
        success: function(data) {
          var user_data;
          user_data = JSON.parse(data);
          Messages.ParticipantInfoCache[user_data['id']] = user_data;
          return Messages.setParticipantInfoUI(Messages.ParticipantInfoCache[participantid]);
        }
      });
    };

    Messages.setParticipantInfoUI = function(participant) {
      var img_url, nameString;
      nameString = "";
      if (participant.first_name != null) {
        nameString += participant.first_name;
        if (participant.last_name != null) {
          nameString += " " + participant.last_name;
        }
      }
      $(".from_participant").html(nameString);
      img_url = "img/head_large.jpg";
      if (participant.profile_img != null) {
        img_url = "/" + participant.profile_img;
      } else if (participant.facebook_id != null) {
        img_url = "https://graph.facebook.com/" + participant.facebook_id + "/picture?width=480";
      }
      return $('#p_pic').attr('src', img_url);
    };

    Messages.loadConversation = function(event) {
      var listing, title, total_count, unread_count;
      $('.messages_list_item').removeClass('selected');
      $(event.currentTarget).addClass('selected').removeClass('unread');
      unread_count = parseInt($(event.currentTarget).find(".notification_count").text(), 10);
      if (unread_count > 0) {
        total_count = parseInt($("#message_count").text(), 10);
        $(event.currentTarget).find(".notification_count").text("0");
        $("#message_count").text(total_count - unread_count);
      }
      this.CurrentConversation = parseInt($(event.delegateTarget).attr('id'));
      this.CurrentParticipantID = $(event.delegateTarget).attr('data-participant');
      $('#message_reply').show();
      $('#participant_info_short').show();
      title = $(event.delegateTarget).attr("data-title");
      listing = $(event.delegateTarget).attr("data-listing");
      $('#listing_title').text(title).attr('href', "/listings/view/" + listing);
      this.refreshParticipantInfo();
      this.refreshUnreadCount();
      return this.refreshMessages(event);
    };

    Messages.loadMessages = function(page, align_bottom, event) {
      var url, _ref,
        _this = this;
      if (align_bottom == null) {
        align_bottom = false;
      }
      if (event == null) {
        event = null;
      }
      if (((_ref = this.DeferredLoadMessages) != null ? _ref.state() : void 0) === "pending") {
        return;
      }
      this.DeferredLoadMessages = new $.Deferred();
      url = myBaseUrl + "messages/getMessages/" + this.CurrentConversation + "/" + page + "/";
      $.get(url, function(data, textStatus) {
        var diff, initial_height, message, message_batch, message_list, messages, _i, _len;
        messages = JSON.parse(data);
        if (messages.error !== void 0) {
          _this.DeferredLoadMessages.resolve();
          return;
        }
        message_list = $('#message_list');
        initial_height = message_list.innerHeight();
        message_batch = "";
        for (_i = 0, _len = messages.length; _i < _len; _i++) {
          message = messages[_i];
          message_batch += create_message_div(message);
        }
        $(message_batch).hide().prependTo('#message_list').fadeIn();
        $('.mli').each(function(index, element) {
          var new_height;
          new_height = $(this).find('.message_buble').height();
          return $(this).css('height', new_height + 'px');
        });
        if (align_bottom) {
          _this.ScrollMessagesTo($("#mli_0"));
        } else {
          diff = message_list.innerHeight() - initial_height;
          $('#current_conversation').scrollTop($('#current_conversation').scrollTop() + diff);
        }
        $('#current_conversation').trigger('scroll');
        if (event != null) {
          _this.attachConversationListItemHandler(event.delegateTarget);
        }
        _this.NumMessagePages = page;
        return _this.DeferredLoadMessages.resolve();
      }).fail(function() {
        return _this.NumMessagePages = 0;
      });
      return this.DeferredLoadMessages.promise();
    };

    Messages.attachConversationListItemHandler = function(container) {
      var _this = this;
      return $(container).one('click', function(event) {
        return _this.loadConversation(event);
      });
    };

    Messages.refreshMessages = function(event) {
      var message_list;
      this.NumMessagePages = 1;
      message_list = $('#message_list');
      message_list.empty();
      $("#loader").show();
      return this.loadMessages(this.NumMessagePages, true, event).always(function() {
        return $("#loader").hide();
      });
    };

    Messages.sendReply = function(event) {
      var message_data, message_text, url,
        _this = this;
      message_text = $('#message_text textarea').val();
      if (message_text.length === 0) {
        A2Cribs.UIManager.Error("Message can not be empty");
        return false;
      }
      $('#send_reply').button('loading');
      message_text = $('#message_text textarea').val();
      message_data = {
        'message_text': message_text,
        'conversation_id': this.CurrentConversation
      };
      url = myBaseUrl + "messages/newMessage/";
      $.post(url, message_data, function(data) {
        var response;
        _this.refreshMessages();
        $('#message_text textarea').val('');
        response = JSON.parse(data);
        if ((data != null ? data.success : void 0) === false) {
          return A2Cribs.UIManager.Error("Something went wrong while sending a reply, please refresh the page and try again");
        }
      }).always(function() {
        return $('#send_reply').button('reset');
      });
      return false;
    };

    Messages.DeleteConversation = function() {
      var request_data, url,
        _this = this;
      url = myBaseUrl + "messages/deleteConversation/";
      request_data = {
        'conv_id': this.CurrentConversation
      };
      return $.post(url, request_data, function(response) {
        var data, e;
        try {
          data = JSON.parse(response);
        } catch (_error) {
          e = _error;
          A2Cribs.UIManager.Error('Failed to delete the conversation');
          return;
        }
        if (data.success === 1) {
          alertify.success('Conversation deleted', 3000);
          _this.CurrentConversation = -1;
          _this.CurrentParticipantID = -1;
          A2Cribs.Dashboard.HideContent('messages');
          return _this.refresh();
        } else {
          return A2Cribs.UIManager.Error('Failed to delete the conversation');
        }
      });
    };

    create_message_div = function(message) {
      return "<div class = 'mli mli-" + message.side + "-side row-fluid' id = 'mli_" + message.count + "' meta = '" + message.id + "'>			<div class = 'span12'>				<div class = 'participant_message_pic'>						<img src = '" + message.pic + "'></img>				</div>				<img src = '/img/messages/arrow-" + message.side + ".png' class = 'arrow-" + message.side + "'></img>				<div class = 'message_bubble'>					<div>						<span class = 'bubble-top-row'>							<strong>" + message.name + ":</strong>							<span class = 'time-ago'>" + message.time_ago + "</span>						</span>						<p class = 'message_body'>" + message.body + "</p>					</div>				</div>			</div>		</div>";
    };

    Messages.Direct = function(directive) {
      var conv_id, participant_id;
      if ((directive.data != null) && directive.classname === "messages") {
        conv_id = parseInt(directive.data.conversation_id);
        this.CurrentConversation = conv_id;
        participant_id = parseInt(directive.data.participant_id);
        this.CurrentParticipantID = participant_id;
        return $('#listing_title').text(directive.data.title);
      }
    };

    Messages.init = function(user) {
      this.me = user;
      this.ViewOnlyUnread = false;
      if (this.CurrentConversation == null) {
        this.CurrentConversation = -1;
      }
      this.DropDownVisible = false;
      this.NumMessagePages = -1;
      if (this.CurrentParticipantID == null) {
        this.CurrentParticipantID = -1;
      }
      this.ParticipantInfoCache = {};
      return this.LoadingMessages = false;
    };

    return Messages;

  }).call(this);

}).call(this);
