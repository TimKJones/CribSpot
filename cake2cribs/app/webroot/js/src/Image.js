(function() {

  A2Cribs.Image = (function() {

    function Image(SubletId, Path, IsPrimary, Caption) {
      this.SubletId = SubletId;
      this.Path = Path;
      this.IsPrimary = IsPrimary;
      this.Caption = Caption;
    }

    return Image;

  })();

}).call(this);
