(function() {

  A2Cribs.NewspaperTest = (function() {

    function NewspaperTest() {}

    NewspaperTest.SendPost = function() {
      return $.ajax({
        url: "http://www.cribspot.com/FeaturedListings/newspaper?secret_token=" + encodeURIComponent("Yx4aPrgs2dhj7tx1VyKQV2OBP53eTFH"),
        type: "GET",
        context: this,
        success: function(response) {
          return console.log(JSON.parse(response));
        },
        failure: function(response) {
          return console.log(response);
        }
      });
    };

    return NewspaperTest;

  })();

}).call(this);
