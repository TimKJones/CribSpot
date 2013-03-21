// Generated by CoffeeScript 1.6.1
(function() {

  A2Cribs.PageHeader = (function() {

    function PageHeader() {}

    PageHeader.renderUnreadConversationsCount = function() {
      var url,
        _this = this;
      url = myBaseUrl + "messages/getUnreadCount";
      return $.get(url, function(data) {
        var count, notification, response_data;
        try {
          response_data = JSON.parse(data);
        } catch (error) {
          return;
        }
        count = response_data.unread_conversations;
        notification = $('#unread-conversation-notification');
        if (count === 0) {
          return notification.hide();
        } else {
          notification.html(response_data.unread_conversations);
          return notification.show();
        }
      });
    };

    return PageHeader;

  })();

}).call(this);
