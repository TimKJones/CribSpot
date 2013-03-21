// Generated by CoffeeScript 1.3.3

/*
ClickBubble class
Wrapper for google infobubble
*/


(function() {

  A2Cribs.ClickBubble = (function() {
    /*
    	Constructor
    	-creates infobubble object
    */

    function ClickBubble(map) {
      var obj;
      obj = {
        map: map,
        arrowStyle: 0,
        arrowPosition: 20,
        shadowStyle: 1,
        borderRadius: 5,
        arrowSize: 17,
        borderWidth: 0,
        disableAutoPan: true,
        padding: 7,
        maxWidth: 350,
        maxHeight: 400,
        disableAnimation: true
      };
      this.InfoBubble = new InfoBubble(obj);
      this.InfoBubble.hideCloseButton();
    }

    /*
    	Opens the tooltip given a marker, with popping animation
    */


    ClickBubble.prototype.Open = function(marker) {
      if (marker) {
        this.SetContent(marker);
        return this.InfoBubble.open(A2Cribs.Map.GMap, marker.GMarker);
      }
    };

    /*
    	Refreshes the tooltip with the new content, no animation
    */


    ClickBubble.prototype.Refresh = function() {
      return this.InfoBubble.open();
    };

    /*
    f	Closes the tooltip, no animation
    */


    ClickBubble.prototype.Close = function() {
      return this.InfoBubble.close();
    };

    /*
    	Sets the content of the tooltip
    */


    ClickBubble.prototype.SetContent = function(marker) {
      var template;
      template = $(".click-bubble:first").wrap('<p/>').parent();
      this.InfoBubble.setContent(template.html());
      return $(".click-bubble:first").unwrap();
    };

    return ClickBubble;

  })();

}).call(this);
