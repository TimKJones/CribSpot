(function() {

  A2Cribs.PhotoManager = (function() {
    var Photo,
      _this = this;

    Photo = (function() {

      function Photo(_div) {
        this._div = _div;
        this._imageId = -1;
        this._isEmpty = true;
        this._isPrimary = false;
        this._caption = "";
        this._path = "";
        this._preview = null;
      }

      Photo.prototype.LoadPhoto = function(_imageId, _path, _caption, isPrimary) {
        var path;
        this._imageId = _imageId;
        this._path = _path;
        this._caption = _caption;
        this._isEmpty = false;
        path = Photo.ProcessImagePath(this._path);
        this._preview = "<img src='" + path + "'></img>";
        this._div.find(".imageContent").html(this._preview);
        return this.SetPrimary(isPrimary);
      };

      Photo.prototype.CreatePreview = function(_file) {
        var reader,
          _this = this;
        this._file = _file;
        if (!Photo.IsAcceptableFileType(this._file.name)) return;
        this._isEmpty = false;
        reader = new FileReader;
        reader.onloadend = function(img) {
          if (typeof img === "object") img = img.target.result;
          _this._preview = "<img src='" + img + "'></img>";
          return _this._div.find(".imageContent").html(_this._preview);
        };
        return reader.readAsDataURL(this._file);
      };

      Photo.prototype.GetPreview = function() {
        return this._preview;
      };

      Photo.prototype.SaveCaption = function(caption) {
        return this._caption = caption;
      };

      Photo.prototype.GetCaption = function() {
        return this._caption;
      };

      Photo.prototype.GetImageId = function() {
        return this._imageId;
      };

      Photo.prototype.IsPrimary = function() {
        return this._isPrimary;
      };

      Photo.prototype.SetPrimary = function(value) {
        this._isPrimary = value;
        if (value) {
          return this._div.find(".primary").addClass('cur-primary');
        } else {
          return this._div.find(".primary").removeClass('cur-primary');
        }
      };

      Photo.prototype.SetId = function(id) {
        return this._imageId = id;
      };

      Photo.prototype.SetPath = function(path) {
        return this._path = path;
      };

      Photo.prototype.SetListingId = function(listing_id) {
        return this._listing_id = listing_id;
      };

      Photo.prototype.IsEmpty = function() {
        return this._isEmpty;
      };

      Photo.prototype.Reset = function() {
        this._isEmpty = true;
        this._div.find(".imageContent").html('<div class="img-place-holder"></div>');
        this._div.find(".image-actions-container").hide();
        this._isPrimary = false;
        this._caption = "";
        this._path = "";
        return this._preview = null;
      };

      Photo.prototype.GetObject = function() {
        return {
          image_id: this._imageId,
          caption: this._caption,
          is_primary: +this._isPrimary,
          image_path: this._path,
          listing_id: this._listing_id
        };
      };

      /*
      		Prepends 'med_' to the filename and returns result
      */

      Photo.ProcessImagePath = function(path) {
        var directory, filename;
        directory = path.substr(0, path.lastIndexOf('/'));
        filename = 'med_' + path.substr(path.lastIndexOf('/') + 1);
        return directory + '/' + filename;
      };

      Photo.IsAcceptableFileType = function(fileName) {
        var fileType, indexOfDot;
        indexOfDot = fileName.indexOf(".", fileName.length - 4);
        if (indexOfDot === -1) return false;
        fileType = fileName.substring(indexOfDot + 1).toLowerCase();
        if (fileType === "jpg" || fileType === "jpeg" || fileType === "png") {
          return true;
        }
        A2Cribs.UIManager.Alert("Not a valid file type. Valid file types include 'jpg', jpeg', or 'png'.");
        return false;
      };

      return Photo;

    })();

    PhotoManager.NUM_PREVIEWS = 6;

    function PhotoManager(div) {
      var _this = this;
      this.div = div;
      this.SetupUI();
      this.CurrentPrimaryImage = 0;
      this.CurrentPreviewImage = null;
      this.CurrentImageLoading = null;
      this.Photos = [];
      this.div.find(".imageContainer").each(function(index, div) {
        return _this.Photos.push(new Photo($(div)));
      });
      this.MAX_CAPTION_LENGTH = 25;
      this.BACKSPACE = 8;
    }

    PhotoManager.prototype.UploadImageDefer = function() {
      this.UploadCompleteDeferred = new $.Deferred();
      return this.UploadCompleteDeferred.promise();
    };

    PhotoManager.prototype.SetupUI = function() {
      var max_file_size, that,
        _this = this;
      that = this;
      this.div.find('.imageContainer').hover(function(event) {
        if ($(event.currentTarget).find('img').length === 1) {
          if (event.type === 'mouseenter') {
            return $(event.currentTarget).find('.image-actions-container').show();
          } else {
            return $(event.currentTarget).find('.image-actions-container').hide();
          }
        }
      });
      this.div.find('#upload_image').click(function() {
        return _this.div.find('#real-file-input').click();
      });
      this.div.find(".imageContent").click(function(event) {
        var index;
        index = +event.currentTarget.id.match(/\d+/g)[0];
        return _this.EditImage(index - 1);
      });
      this.div.find(".edit").click(function(event) {
        var index;
        index = +event.currentTarget.id.match(/\d+/g)[0];
        return _this.EditImage(index - 1);
      });
      this.div.find(".delete").click(function(event) {
        var index;
        index = +event.currentTarget.id.match(/\d+/g)[0];
        return _this.DeleteImage(index - 1);
      });
      this.div.find(".primary").click(function(event) {
        var index;
        index = +event.currentTarget.id.match(/\d+/g)[0];
        return _this.MakePrimary(index - 1);
      });
      this.div.find("#captionInput").keyup(function() {
        var curString;
        curString = _this.div.find("#captionInput").val();
        if (curString.length === _this.MAX_CAPTION_LENGTH) {
          _this.div.find("#charactersLeft").css("color", "red");
        } else {
          _this.div.find("#charactersLeft").css("color", "black");
        }
        _this.div.find("#charactersLeft").html(_this.MAX_CAPTION_LENGTH - curString.length);
        if (_this.CurrentPreviewImage != null) {
          return _this.Photos[_this.CurrentPreviewImage].SaveCaption(_this.div.find("#captionInput").val());
        }
      });
      this.div.find(".delete").tooltip({
        'selector': '',
        'placement': 'bottom',
        'title': 'Delete'
      });
      this.div.find(".edit").tooltip({
        'selector': '',
        'placement': 'bottom',
        'title': 'Edit'
      });
      this.div.find(".primary").tooltip({
        'selector': '',
        'placement': 'bottom',
        'title': 'Make Primary'
      });
      max_file_size = 5000000;
      return this.div.find('#ImageAddForm').fileupload({
        url: myBaseUrl + 'images/AddImage',
        dataType: 'json',
        acceptFileTypes: /(\.|\/)(jpeg|jpg|png)$/i,
        singleFileUploads: true,
        maxFileSize: max_file_size,
        loadImageMaxFileSize: max_file_size,
        disableImageResize: false,
        previewMaxWidth: 100,
        previewMaxHeight: 100,
        previewCrop: true
      }).on('fileuploadsend', function(e, data) {
        if (data.files[0].type.indexOf("image") === -1) {
          A2Cribs.UIManager.Error("Sorry - Please only upload png, jpeg or jpg!");
          return false;
        }
        if (data.files[0].size > max_file_size) {
          A2Cribs.UIManager.Error("Sorry - the image you uploaded was too large!");
          return false;
        }
        if (_this.NextAvailablePhoto() === -1) {
          A2Cribs.UIManager.Error("Sorry - you can't upload more than 6 photos at this time.");
          return false;
        }
        _this.UploadImageDefer();
        _this.div.find("#upload_image").button('loading');
        if ((_this.CurrentImageLoading = _this.NextAvailablePhoto()) >= 0 && (data.files != null) && (data.files[0] != null)) {
          _this.Photos[_this.CurrentImageLoading].CreatePreview(data.files[0], _this.div.find("#imageContent" + (_this.CurrentImageLoading + 1)));
        }
        return true;
      }).on('fileuploaddone', function(e, data) {
        _this.div.find("#upload_image").button('reset');
        if ((data.result.errors != null) && data.result.errors.length) {
          A2Cribs.UIManager.Error("Failed to upload image!");
          _this.UploadCompleteDeferred.resolve(null);
          return _this.Photos[_this.CurrentImageLoading].Reset();
        } else {
          _this.Photos[_this.CurrentImageLoading].SetId(data.result.image_id);
          _this.Photos[_this.CurrentImageLoading].SetPath(data.result.image_path);
          if (_this.PhotoCount() === 1) {
            _this.MakePrimary(_this.CurrentImageLoading);
          }
          return _this.UploadCompleteDeferred.resolve(true);
        }
      }).on('fileuploadfail', function(e, data) {
        if (_this.CurrentImageLoading) {
          A2Cribs.UIManager.Error("Failed to upload image!");
          _this.div.find("#upload_image").button('reset');
          return _this.Photos[_this.CurrentImageLoading].Reset();
        }
      });
    };

    PhotoManager.prototype.Open = function(image_array, imageCallback, row) {
      if (row == null) row = null;
      this.LoadImages(image_array, imageCallback, row);
      return this.div.modal('show');
    };

    PhotoManager.prototype.LoadImages = function(image_array, imageCallback, row) {
      var image, _i, _len,
        _this = this;
      if (row == null) row = null;
      this.Reset();
      if ((image_array != null ? image_array.length : void 0) != null) {
        for (_i = 0, _len = image_array.length; _i < _len; _i++) {
          image = image_array[_i];
          this.Photos[this.NextAvailablePhoto()].LoadPhoto(image.image_id, image.image_path, image.caption, image.is_primary);
        }
      }
      this.div.find("#finish_photo").unbind('click');
      return this.div.find("#finish_photo").click(function() {
        A2Cribs.UIManager.Success("Cribspot is still uploading your images. We'll be done in just a moment.");
        /*
        			FIXING GITHUB ISSUE 141
        			Need to wait until image save is complete before attempting to save row, or data isn't in cache yet
        */
        return $.when(_this.UploadCompleteDeferred).then(function(resolved) {
          if (resolved) {
            imageCallback(_this.GetPhotos(), row);
            return _this.div.modal('hide');
          }
        });
      });
    };

    PhotoManager.prototype.NextAvailablePhoto = function() {
      var i, photo, _len, _ref;
      _ref = this.Photos;
      for (i = 0, _len = _ref.length; i < _len; i++) {
        photo = _ref[i];
        if (photo.IsEmpty()) return i;
      }
      return -1;
    };

    PhotoManager.prototype.PhotoCount = function() {
      var count, i, photo, _len, _ref;
      count = 0;
      _ref = this.Photos;
      for (i = 0, _len = _ref.length; i < _len; i++) {
        photo = _ref[i];
        if (!photo.IsEmpty()) count += 1;
      }
      return count;
    };

    PhotoManager.prototype.DeleteImage = function(index) {
      var _this = this;
      return $.ajax({
        url: myBaseUrl + "images/delete/" + this.Photos[index].GetImageId(),
        type: "GET",
        success: function() {
          var i, photo, _len, _ref;
          _this.Photos[index].Reset();
          if (index === _this.CurrentPrimaryImage) {
            _ref = _this.Photos;
            for (i = 0, _len = _ref.length; i < _len; i++) {
              photo = _ref[i];
              if (!photo.IsEmpty()) _this.MakePrimary(i);
            }
          }
          if (index === _this.CurrentPreviewImage) {
            return _this.div.find("#imageContent0").html('<div class="img-place-holder"></div>');
          }
        }
      });
    };

    PhotoManager.prototype.EditImage = function(index) {
      if (!this.Photos[index].IsEmpty()) {
        this.CurrentPreviewImage = index;
        this.div.find("#imageContent0").html(this.Photos[index].GetPreview());
        this.div.find("#captionInput").val(this.Photos[index].GetCaption());
        return this.div.find("#charactersLeft").html(this.MAX_CAPTION_LENGTH - this.Photos[index].GetCaption().length);
      }
    };

    PhotoManager.prototype.MakePrimary = function(index) {
      this.Photos[this.CurrentPrimaryImage].SetPrimary(false);
      return this.Photos[this.CurrentPrimaryImage = index].SetPrimary(true);
    };

    PhotoManager.prototype.Reset = function() {
      var photo, _i, _len, _ref, _results;
      this.div.find("#imageContent0").html('<div class="img-place-holder"></div>');
      this.div.find("#captionInput").val("");
      this.div.find("#charactersLeft").html(this.MAX_CAPTION_LENGTH);
      this.UploadCompleteDeferred = new $.Deferred().resolve(true);
      _ref = this.Photos;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        photo = _ref[_i];
        _results.push(photo.Reset());
      }
      return _results;
    };

    PhotoManager.prototype.GetPhotos = function() {
      var photo, results, _i, _len, _ref;
      results = [];
      _ref = this.Photos;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        photo = _ref[_i];
        if (!photo.IsEmpty()) results.push(photo.GetObject());
      }
      if (results.length === 0) {
        return null;
      } else {
        return results;
      }
    };

    /*
    	Send photo and photo's row_id to server
    	The form of this function is mostly for testing the backend handling of row_id.
    */

    PhotoManager.SubmitPhoto = function(row_id, photo) {
      var _this = this;
      return $.ajax({
        url: myBaseUrl + "images/AddImage/" + row_id + "/" + photo,
        type: "POST",
        success: function(response) {
          response = JSON.parse(response);
          return console.log(response);
        }
      });
    };

    /*
    	Picture Modal On Ready
    	Waits for the picture modal element to be loaded
    	before initializing the PhotoManager
    */

    $(document).ready(function() {
      if ($("#picture-modal").length) {
        return A2Cribs.PhotoManager = new A2Cribs.PhotoManager($("#picture-modal"));
      }
    });

    return PhotoManager;

  }).call(this);

}).call(this);
