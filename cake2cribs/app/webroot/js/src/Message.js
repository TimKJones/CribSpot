// Generated by CoffeeScript 1.4.0
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
      return this.refresh();
    };

    Messages.ScrollMessagesTo = function(mli) {
      var cc, dist;
      cc = $('#current_conversation');
      dist = (cc.offset().top + cc.innerHeight()) - (mli.offset().top + mli.innerHeight() + 10);
      return cc.scrollTop(cc.scrollTop() - dist);
    };

    Messages.toggleDropdown = function() {
      this.DropDownVisible = !this.DropDownVisible;
      $('#conversation_drop_down').slideToggle('fast');
      $('#toggle-conversations i').toggleClass('icon-caret-right', !this.DropDownVisible);
      $('#toggle-conversations i').toggleClass('icon-caret-down', this.DropDownVisible);
      $('#conversations_list_header').toggleClass('shadowed', this.DropDownVisible);
      $('#conversations_list_header').toggleClass('expanded', this.DropDownVisible);
      $('#conversations_list_header').toggleClass('minimized', !this.DropDownVisible);
      return $('.messages-content').toggleClass('hidden', !this.DropDownVisible);
    };

    Messages.toggleMessageContent = function() {};

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
        if (!$("#conversation_drop_down").is(":visible")) {
          return this.toggleDropdown();
        }
      }
    };

    Messages.refreshUnreadCount = function() {
      var url,
        _this = this;
      url = myBaseUrl + "messages/getUnreadCount";
      return $.get(url, function(data) {
        var response_data;
        response_data = JSON.parse(data);
        return $('#unread_conversations_count span').html(response_data.unread_conversations);
      });
    };

    Messages.refreshConversations = function() {
      var url,
        _this = this;
      url = myBaseUrl + "messages/getConversations";
      if (this.ViewOnlyUnread) {
        url += '?only_unread=1';
      }
      return $.get(url, function(data) {
        var SelectedConversationDiv;
        $('.conversation_list').html(data);
        $('#conversation_count').html($('.conversation_list_item').length);
        SelectedConversationDiv = $('#cli_' + _this.CurrentConversation);
        SelectedConversationDiv.addClass('selected_conversation');
        SelectedConversationDiv.removeClass('unread_conversation');
        return $('.conversation_list_item').each(function(index, element) {
          return $(element).click(function(event) {
            return _this.loadConversation(event);
          });
        });
      });
    };

    Messages.toggleUnreadConversations = function() {
      this.ViewOnlyUnread = $('#view_unread_cb').is(':checked');
      return this.refreshConversations();
    };

    Messages.refreshParticipantInfo = function() {
      var participantid, url,
        _this = this;
      participantid = $('#cli_' + this.CurrentConversation).find('meta').attr('participantid');
      if (this.ParticipantInfoCache[participantid] != null) {
        this.setParticipantInfoUI(this.ParticipantInfoCache[participantid]);
        return;
      }
      url = url = myBaseUrl + "messages/getParticipantInfo/" + this.CurrentConversation + "/";
      return $.get(url, function(data) {
        var user_data;
        user_data = JSON.parse(data);
        _this.ParticipantInfoCache[user_data['id']] = user_data;
        return _this.setParticipantInfoUI(_this.ParticipantInfoCache[participantid]);
      });
    };

    Messages.setParticipantInfoUI = function(participant) {
      $(".from_participant").html(participant['first_name']).attr('href', myBaseUrl + 'users/view/' + participant['id']);
      return $("#participant_university").html(participant['University']['name']);
    };

    Messages.loadConversation = function(event) {
      var title;
      $('#cli_' + this.CurrentConversation).removeClass('selected_conversation');
      $('#cli_' + this.CurrentConversation).addClass('read_conversation');
      this.CurrentConversation = parseInt($(event.currentTarget).addClass('selected_conversation').removeClass('unread_conversation').attr('convid'));
      $('#message_reply').show();
      $('#participant_info_short').show();
      title = $('#cli_' + this.CurrentConversation).find('.conversation_title').text();
      $('#listing_title').text(title);
      this.refreshParticipantInfo();
      this.refreshUnreadCount();
      return this.refreshMessages();
    };

    Messages.loadMessages = function(page, align_bottom) {
      var url,
        _this = this;
      if (align_bottom == null) {
        align_bottom = false;
      }
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
        return $('#current_conversation').trigger('scroll');
      }).fail(function() {
        return _this.NumMessagePages = 0;
      });
    };

    Messages.refreshMessages = function(event) {
      this.NumMessagePages = 1;
      $('#message_list').html('');
      return this.loadMessages(this.NumMessagePages, true);
    };

    Messages.sendReply = function(event) {
      var message_data, message_text, url,
        _this = this;
      $('#send_reply').attr('disabled', 'disabled');
      message_text = $('#message_text textarea').val();
      message_data = {
        'message_text': message_text,
        'conversation_id': this.CurrentConversation
      };
      url = myBaseUrl + "messages/newMessage/";
      $.post(url, message_data, function(data) {
        _this.refreshMessages();
        _this.refreshConversations();
        $('#message_text textarea').val('');
        return $('#send_reply').removeAttr('disabled');
      });
      return false;
    };

    Messages.init = function(current_conversation) {
      if (current_conversation == null) {
        current_conversation = -1;
      }
      this.ViewOnlyUnread = false;
      this.CurrentConversation = current_conversation;
      this.DropDownVisible = false;
      this.NumMessagePages = -1;
      return this.ParticipantInfoCache = {};
    };

    return Messages;

  })();

}).call(this);
