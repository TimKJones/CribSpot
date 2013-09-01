(function() {

  A2Cribs.Messages = (function() {

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
      this.NumMessagePages += 1;
      return this.loadMessages(this.NumMessagePages);
    };

    Messages.refresh = function() {
      this.refreshUnreadCount();
      this.refreshConversations();
      if (this.CurrentConversation !== -1) {
        this.refreshParticipantInfo();
        this.refreshMessages();
      }
      return alert('loaded');
    };

    Messages.refreshUnreadCount = function() {
      var url,
        _this = this;
      url = myBaseUrl + "messages/getUnreadCount";
      return $.get(url, function(data) {
        var response_data;
        response_data = JSON.parse(data);
        return $('#message_count').html(response_data.unread_conversations);
      });
    };

    Messages.refreshConversations = function() {
      var url,
        _this = this;
      url = myBaseUrl + "messages/getConversations";
      return $.get(url, function(data) {
        var conversations, convo, list_item, _i, _len;
        conversations = JSON.parse(data);
        for (_i = 0, _len = conversations.length; _i < _len; _i++) {
          convo = conversations[_i];
          list_item = $("<li />", {
            text: convo.Conversation.title,
            "class": "messages_list_item",
            id: convo.Conversation.conversation_id,
            "data-participant": convo.Participant.id
          });
          $("#messages_list_content").append(list_item);
        }
        return _this.attachConversationListItemHandler();
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
      $(".from_participant").html("" + participant.first_name + " " + participant.last_name);
      return A2Cribs.VerifyManager.getVerificationFor(participant).then(function(verification_info) {
        var url, veripanel;
        veripanel = $('#verification-panel');
        if (verification_info.verified_email) {
          veripanel.find('#veri-email  i:last-child').removeClass('unverified icon-remove-sign').addClass('verified icon-ok-sign');
        }
        if (verification_info.verified_fb) {
          url = "https://graph.facebook.com/" + verification_info.fb_id + "/picture?width=480";
          console.log(url);
          return $('#p_pic').attr('src', url);
        }
      });
    };

    Messages.loadConversation = function(event) {
      /*
      		$('#cli_' + @CurrentConversation).removeClass 'selected_conversation'
      		$('#cli_' + @CurrentConversation).addClass 'read_conversation'	
      
      		$(event.currentTarget)
      			.addClass('selected_conversation')
      			.removeClass('unread_conversation')
      */
      var sublet_url, title;
      this.CurrentConversation = parseInt($(event.delegateTarget).attr('id'));
      this.CurrentParticipantID = $(event.delegateTarget).attr('data-participant');
      $('#message_reply').show();
      $('#participant_info_short').show();
      title = $('#cli_' + this.CurrentConversation).find('.conversation_title').text();
      sublet_url = $('#cli_' + this.CurrentConversation + ' a').attr('href');
      $('#listing_title').text(title).attr('href', sublet_url);
      this.refreshParticipantInfo();
      this.refreshUnreadCount();
      return this.refreshMessages();
    };

    Messages.loadMessages = function(page, align_bottom) {
      var url,
        _this = this;
      if (align_bottom == null) align_bottom = false;
      url = myBaseUrl + "messages/getMessages/" + this.CurrentConversation + "/" + page + "/";
      return $.get(url, function(data, textStatus) {
        var diff, initial_height, message_list;
        message_list = $('#message_list');
        initial_height = message_list.innerHeight();
        $(data).hide().prependTo('#message_list').fadeIn();
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
        return _this.attachConversationListItemHandler();
      }).fail(function() {
        return _this.NumMessagePages = 0;
      });
    };

    Messages.attachConversationListItemHandler = function() {
      var _this = this;
      return $('.messages_list_item').one('click', function(event) {
        return _this.loadConversation(event);
      });
    };

    Messages.refreshMessages = function(event) {
      var message_list;
      this.NumMessagePages = 1;
      message_list = $('#message_list');
      message_list.html('');
      return this.loadMessages(this.NumMessagePages, true);
    };

    Messages.sendReply = function(event) {
      var message_data, message_text, url,
        _this = this;
      message_text = $('#message_text textarea').val();
      if (message_text.length === 0) {
        A2Cribs.UIManager.Error("Message can not be empty");
        return false;
      }
      $('#send_reply').attr('disabled', 'disabled');
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
        return $('#send_reply').removeAttr('disabled');
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
        var data;
        try {
          data = JSON.parse(response);
        } catch (e) {
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

    Messages.Direct = function(directive) {
      var conv_id, participant_id;
      if (directive.data != null) {
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
      if (!(this.CurrentConversation != null)) this.CurrentConversation = -1;
      this.DropDownVisible = false;
      this.NumMessagePages = -1;
      if (!(this.CurrentParticipantID != null)) this.CurrentParticipantID = -1;
      this.ParticipantInfoCache = {};
      return this.LoadingMessages = false;
    };

    return Messages;

  }).call(this);

}).call(this);
