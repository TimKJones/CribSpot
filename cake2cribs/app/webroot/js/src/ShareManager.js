(function() {

  A2Cribs.ShareManager = (function() {

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
      url = 'http://cribspot.com/listing/' + listing_id;
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
      fbObj = {
        method: 'feed',
        link: url,
        picture: 'http://www.cribspot.com/img/upright_logo.png',
        name: building_name,
        caption: caption
      };
      if (description !== null) fbObj['description'] = description;
      return FB.ui(fbObj);
    };

    ShareManager.CopyListingUrl = function(listing_id, street_address, city, state, zip) {
      var url;
      url = this.GetShareUrl(listing_id, street_address, city, state, zip);
      return window.prompt("Copy to clipboard: Ctrl+C, Enter", url);
    };

    ShareManager.ShareListingOnTwitter = function(listing_id, street_address, city, state, zip) {
      var url, x, y;
      url = this.GetTwitterShareUrl(listing_id, street_address, city, state, zip);
      x = screen.width / 2 - 600 / 2;
      y = screen.height / 2 - 350 / 2;
      return window.open(url, 'winname', "directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=350,top=" + y + ",left=" + x);
    };

    ShareManager.GetTwitterShareUrl = function(listing_id, street_address, city, state, zip) {
      var url;
      if (listing_id === null || street_address === null || city === null || state === null || zip === null) {
        return null;
      }
      url = this.GetShareUrl(listing_id, street_address, city, state, zip);
      return 'https://twitter.com/share?url=' + encodeURIComponent(url) + '&text=' + encodeURIComponent('Check out this listing on Cribspot!') + '&via=TheCribspot';
    };

    ShareManager.InitTweetButton = function(listing_id, street_address, city, state, zip) {
      var tweetBtn, url;
      if (listing_id === null || street_address === null || city === null || state === null || zip === null) {
        return null;
      }
      url = this.GetShareUrl(listing_id, street_address, city, state, zip);
      $('#twitterDiv iframe').remove();
      tweetBtn = $('<a></a>').addClass('twitter-share-button').attr('href', 'http://twitter.com/share').attr('data-url', url).attr('data-text', 'Check out this awesome property on Cribspot.com! ' + url).attr('data-via', 'TheCribspot');
      $('#twitterDiv').append(tweetBtn);
      return twttr.widgets.load();
    };

    return ShareManager;

  })();

}).call(this);
