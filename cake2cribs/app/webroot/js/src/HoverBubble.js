// Generated by CoffeeScript 1.3.3

/*
HoverBubble class
Wrapper for google infobubble
*/


(function() {

  A2Cribs.HoverBubble = (function() {
    /*
    	Constructor
    	-creates infobubble object
    */

    function HoverBubble(map) {
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
        maxWidth: 300,
        maxHeight: 200,
        disableAnimation: true
      };
      this.InfoBubble = new InfoBubble(obj);
      this.InfoBubble.hideCloseButton();
    }

    /*
    	Opens the tooltip given a marker, with popping animation
    */


    HoverBubble.prototype.Open = function(marker) {
      if (marker) {
        this.SetContent(marker);
        return this.InfoBubble.open(A2Cribs.Map.GMap, marker.GMarker);
      }
    };

    /*
    	Refreshes the tooltip with the new content, no animation
    */


    HoverBubble.prototype.Refresh = function() {
      return this.InfoBubble.open();
    };

    /*
    f	Closes the tooltip, no animation
    */


    HoverBubble.prototype.Close = function() {
      return this.InfoBubble.close();
    };

    /*
    	Sets the content of the tooltip
    */


    HoverBubble.prototype.SetContent = function(marker) {
      var content, hoverdata, template;
      hoverdata = A2Cribs.Cache.MarkerIdToHoverDataMap[marker.MarkerId];
      template = $(".hover-bubble:first").wrap('<p/>').parent();
      content = template.children().first();
      if (hoverdata.NumListings > 1) {
        content.find('.hover-listing-count').text(hoverdata.NumListings + " Listings:");
        if (hoverdata.MinBeds === hoverdata.MaxBeds) {
          content.find('.hover-bed-count').text(hoverdata.MinBeds);
        } else {
          content.find('.hover-bed-count').text(hoverdata.MinBeds + "-" + hoverdata.MaxBeds);
        }
        if (hoverdata.MinRent === hoverdata.MaxRent) {
          content.find('.hover-price').text("$" + hoverdata.MinRent);
        } else {
          content.find('.hover-price').text("$" + hoverdata.MinRent + "-$" + hoverdata.MaxRent);
        }
      } else {
        content.find('.hover-listing-count').empty();
        content.find('.hover-bed-count').text(hoverdata.MinBeds);
        content.find('.hover-price').text("$" + hoverdata.MinRent);
      }
      content.find('.hover-apt-type').text(hoverdata.UnitType);
      content.find('.hover-date-range').text(this.resolveDate(hoverdata.MinDate, hoverdata.MaxDate));
      this.InfoBubble.setContent(template.html());
      return $(".hover-bubble:first").unwrap();
    };

    HoverBubble.prototype.resolveDate = function(minDate, maxDate) {
      var maxSplit, minSplit;
      minSplit = minDate.split("-");
      maxSplit = maxDate.split("-");
      return +minSplit[1] + "/" + +minSplit[2] + "-" + +maxSplit[1] + "/" + +maxSplit[2];
    };

    return HoverBubble;

  })();

}).call(this);
