(function() {

  A2Cribs.SubletOwner = (function() {

    function SubletOwner(user) {
      this.FirstName = user.first_name;
      this.facebook_userid = user.facebook_userid;
      this.VerifiedUniversity = user.verified_university;
      this.university_verified = user.university_verified;
      this.twitter_userid = user.twitter_userid;
      this.verified = user.verified;
      this.id = user.id;
    }

    return SubletOwner;

  })();

}).call(this);
