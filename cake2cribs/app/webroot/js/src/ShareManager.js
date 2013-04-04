(function() {

  A2Cribs.ShareManager = (function() {

    function ShareManager() {}

    /*
    	Prompts user to share listing on facebook using facebook api.
    	INPUT:
    		university_encoded = University with spaces replaced by '_' (as it is in url)
    		address_encoded = address for listing encoded in the same fasion
    		listing_id = listing to be shared
    */

    ShareManager.GetShareUrl = function(university_encoded, address_encoded, sublet_id) {
      var url;
      if (university_encoded == null) university_encoded = null;
      if (address_encoded == null) address_encoded = null;
      if (sublet_id == null) sublet_id = null;
      if (university_encoded === null || address_encoded === null || sublet_id === null) {
        return;
      }
      url = 'http://cribspot.com/sublet/' + university_encoded + '/' + address_encoded + '/' + sublet_id;
      return url;
    };

    ShareManager.ShareListingOnFacebook = function(university_encoded, address_encoded, sublet_id, description) {
      var address, fbObj, url;
      if (university_encoded == null) university_encoded = null;
      if (address_encoded == null) address_encoded = null;
      if (sublet_id == null) sublet_id = null;
      if (description == null) description = null;
      url = A2Cribs.ShareManager.GetShareUrl(university_encoded, address_encoded, sublet_id);
      /*sublet = null
      		if A2Cribs.Map.IdToListingMap[sublet_id] != undefined
      			sublet = A2Cribs.Map.IdToListingMap[sublet_id]
      		else
      			A2Cribs.Map.GetSubletData sublet_id
      */
      address = null;
      if (description === null) {
        address = A2Cribs.Cache.IdToMarkerMap[A2Cribs.Cache.IdToSubletMap[sublet_id].MarkerId].Address;
        description = A2Cribs.Cache.IdToSubletMap[sublet_id].Description;
      } else {
        address = address_encoded.split("_").join(" ");
      }
      fbObj = {
        method: 'feed',
        link: url,
        picture: 'http://www.cribspot.com/img/upright_logo.png',
        name: address,
        caption: 'Check out this listing on Cribspot!',
        description: description
      };
      return FB.ui(fbObj);
    };

    ShareManager.GetTwitterShareUrl = function(university_encoded, address_encoded, sublet_id) {
      var url;
      if (university_encoded == null) university_encoded = null;
      if (address_encoded == null) address_encoded = null;
      if (sublet_id == null) sublet_id = null;
      if (university_encoded === null || address_encoded === null || sublet_id === null) {
        return "";
      }
      url = A2Cribs.ShareManager.GetShareUrl(university_encoded, address_encoded, sublet_id);
      return 'https://twitter.com/share?url=' + encodeURIComponent(url) + '&text=' + encodeURIComponent('Check out this listing on Cribspot!') + '&via=TheCribspot';
    };

    ShareManager.InitTweetButton = function(university_encoded, address_encoded, sublet_id) {
      var tweetBtn, url;
      if (university_encoded == null) university_encoded = null;
      if (address_encoded == null) address_encoded = null;
      if (sublet_id == null) sublet_id = null;
      if (university_encoded === null || address_encoded === null || sublet_id === null) {
        return;
      }
      url = A2Cribs.ShareManager.GetShareUrl(university_encoded, address_encoded, sublet_id);
      $('#twitterDiv iframe').remove();
      tweetBtn = $('<a></a>').addClass('twitter-share-button').attr('href', 'http://twitter.com/share').attr('data-url', url).attr('data-text', 'Check out my sublease on Cribspot.com! ' + url).attr('data-via', 'TheCribspot');
      $('#twitterDiv').append(tweetBtn);
      return twttr.widgets.load();
    };

    ShareManager.GetSubletData = function(sublet_id) {
      return $.ajax({
        url: myBaseUrl + "Sublets/GetSubletData/" + sublet_id,
        type: "GET",
        success: A2Cribs.Map.GetSubletDataCallback
      });
    };

    return ShareManager;

  })();

}).call(this);
