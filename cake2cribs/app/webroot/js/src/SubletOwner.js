(function() {

  A2Cribs.SubletOwner = (function() {

    function SubletOwner(FirstName, FBUserId, VerifiedUniversity, TwitterFollowers) {
      this.FirstName = FirstName;
      this.FBUserId = FBUserId;
      this.VerifiedUniversity = VerifiedUniversity;
      this.TwitterFollowers = TwitterFollowers;
    }

    return SubletOwner;

  })();

}).call(this);
