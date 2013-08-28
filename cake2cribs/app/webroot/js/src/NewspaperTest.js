(function() {

  A2Cribs.NewspaperTest = (function() {

    function NewspaperTest() {}

    NewspaperTest.SendPost = function() {
      return $.ajax({
        url: "http://ec2-54-244-203-91.us-west-2.compute.amazonaws.com/FeaturedListings/newspaper?secret_token=" + encodeURIComponent("Yx4+aP%gs2dh2uG?1VyKQV2OBP-3eKBI"),
        type: "POST",
        context: this,
        success: function(response) {
          return console.log(response);
        },
        failure: function(response) {
          return console.log(response);
        }
      });
    };

    return NewspaperTest;

  })();

}).call(this);
