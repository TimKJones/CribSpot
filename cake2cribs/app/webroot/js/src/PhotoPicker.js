(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  A2Cribs.PhotoPicker = (function() {
    var Photo,
      _this = this;

    function PhotoPicker() {}

    PhotoPicker.MAX_CAPTION_LENGTH = 25;

    PhotoPicker.MAX_PHOTOS = 24;

    PhotoPicker.MAX_FILE_SIZE = 5000000;

    /*
    	Class Photo
    	Holds all the information for each photo
    	also has some methods to make
    */

    Photo = (function() {
      /*
      		Photo Constructor
      		Takes integer for the index in the photo array,
      		an object that is either a preview for the image
      		to be saved or is the complete photo. A deferred
      		object is included if the photo has yet to be
      		saved.
      */
      function Photo(index, object, deferred) {
        this.index = index;
        if (deferred == null) deferred = null;
        this.Resolve = __bind(this.Resolve, this);
        if (deferred != null) {
          this.SetPreview(object);
          deferred.done(this.Resolve);
        } else {
          ({
            image_id: this._image_id = object.image_id
          });
          this.SaveCaption(object.caption);
          this._is_primary = object.is_primary;
          this._path = object.image_path;
          this._listing_id = object.listing_id;
        }
      }

      /*
      		Set Preview
      		Sets the preview of the sent object
      */

      Photo.prototype.SetPreview = function(preview) {
        this.preview = preview;
      };

      /*
      		Resolve
      		When the photo has been saved to the backend,
      		the resolve callback is called saving the other
      		parts of the image object to this photo
      */

      Photo.prototype.Resolve = function(data) {
        console.log(data.result);
        this._image_id = data.result.image_id;
        this._path = data.result.image_path;
        return this._listing_id = data.result.listing_id;
      };

      /*
      		Edit
      		Triggers an edit event to make the image
      		appear in the edit window
      */

      Photo.prototype.Edit = function() {
        return $(".image-row").trigger("edit_image", [this.index]);
      };

      /*
      		Delete
      		Triggers a delete on the index
      */

      Photo.prototype.Delete = function() {
        this.DeleteDeferred = null;
        if (this._image_id != null) {
          this.DeleteDeferred = $.ajax({
            url: myBaseUrl + "images/delete/" + this._image_id,
            type: "GET"
          });
        }
        return $(".image-row").trigger("delete_image", [this.index]);
      };

      /*
      		Make Primary
      */

      Photo.prototype.MakePrimary = function() {
        this._is_primary = true;
        return this.div.find(".primary").addClass('cur-primary');
      };

      /*
      		Unset Primary
      */

      Photo.prototype.UnsetPrimary = function() {
        this._is_primary = false;
        return this.div.find(".primary").removeClass('cur-primary');
      };

      Photo.prototype.IsPrimary = function() {
        if (this._is_primary != null) {
          return this._is_primary;
        } else {
          return false;
        }
      };

      /*
      		Get Preview
      		Returns either a canvas or an image of the photo
      */

      Photo.prototype.GetPreview = function() {
        var context, newCanvas, path_splice;
        if (this.preview != null) {
          newCanvas = document.createElement('canvas');
          context = newCanvas.getContext('2d');
          newCanvas.width = this.preview.width;
          newCanvas.height = this.preview.height;
          context.drawImage(this.preview, 0, 0);
          return newCanvas;
        } else {
          path_splice = this._path.split("/");
          return $("<img src='/" + path_splice[0] + "/" + path_splice[1] + "/med_" + path_splice[2] + "'>");
        }
      };

      /*
      		Get Caption
      		Returns the caption of the photo
      */

      Photo.prototype.GetCaption = function() {
        if (this._caption != null) {
          return this._caption;
        } else {
          return "";
        }
      };

      /*
      		Save Caption
      		Sets the caption of the photo
      */

      Photo.prototype.SaveCaption = function(_caption) {
        this._caption = _caption;
      };

      /*
      		Get Object
      		Returns the necessary fields for A2Cribs.Image
      		conversion
      */

      Photo.prototype.GetObject = function() {
        return {
          image_id: this._image_id,
          caption: this.GetCaption(),
          is_primary: +this.IsPrimary(),
          image_path: this._path,
          listing_id: this._listing_id
        };
      };

      /*
      		Get Html
      		Returns the html for the image container for 
      		this photo
      */

      Photo.prototype.GetHtml = function() {
        var path_splice,
          _this = this;
        if (!(this.div != null)) {
          this.div = $("<div id=\"prev_" + this.index + "\" class=\"imageContainer span6\">\n	<div class=\"imageContent imageThumb\"></div>\n	<div class = 'image-actions-container'>\n		<i class=\"delete icon-trash\"></i>\n		<i class=\"edit icon-edit\"></i>\n		<i class=\"primary icon-asterisk\"></i>\n	</div>\n</div>");
          if (this.preview != null) {
            this.div.find(".imageContent").html(this.preview);
          } else {
            path_splice = this._path.split("/");
            this.div.find(".imageContent").html("<img src='/" + path_splice[0] + "/" + path_splice[1] + "/med_" + path_splice[2] + "'>");
          }
          this.div.find(".delete").click(function() {
            return _this.Delete();
          });
          this.div.find(".edit, .imageContent").click(function() {
            return _this.Edit();
          });
          this.div.find(".primary").click(function() {
            return $(".image-row").trigger("set_primary", [_this.index]);
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
        }
        return this.div;
      };

      return Photo;

    })();

    /*
    	SetupUI
    	Attaches listeners to all the UI elements
    	in the photo manager
    */

    PhotoPicker.SetupUI = function(div) {
      var _this = this;
      this.div = div;
      this.Reset();
      this.div.find(".image-row").on("delete_image", function(event, index) {
        return _this.Delete(index);
      });
      this.div.find(".image-row").on("edit_image", function(event, index) {
        return _this.Edit(index);
      });
      this.div.find(".image-row").on("set_primary", function(event, index) {
        return _this.MakePrimary(index);
      });
      this.div.find('#upload_image').click(function() {
        return _this.div.find('#real-file-input').click();
      });
      this.div.find("#captionInput").keyup(function() {
        var caption;
        caption = _this.div.find("#captionInput").val();
        if (caption.length >= _this.MAX_CAPTION_LENGTH) {
          _this.div.find("#charactersLeft").css("color", "red");
        } else {
          _this.div.find("#charactersLeft").css("color", "black");
        }
        _this.div.find("#charactersLeft").html(_this.MAX_CAPTION_LENGTH - caption.length);
        return _this._photos[_this.CurrentPreviewImage].SaveCaption(caption);
      });
      return this.div.find('#ImageAddForm').fileupload({
        url: myBaseUrl + 'images/AddImage',
        dataType: 'json',
        acceptFileTypes: /(\.|\/)(jpeg|jpg|png)$/i,
        singleFileUploads: true,
        maxFileSize: this.MAX_FILE_SIZE,
        loadImageMaxFileSize: this.MAX_FILE_SIZE,
        disableImageResize: false,
        previewMaxWidth: 300,
        previewMaxHeight: 300,
        previewCrop: true
      }).on('fileuploadadd', function(e, data) {
        if (_this._photos.size === 24) {
          A2Cribs.UIManager.Error("Sorry but Cribspot only allows for 24 pictures!");
          return false;
        }
      }).on('fileuploadprocessalways', function(e, data) {
        var file, index;
        index = data.index;
        file = data.files[index];
        if (file.error != null) {
          A2Cribs.UIManager.Error("Sorry - " + file.error);
          return false;
        }
        if (file.preview != null) {
          _this.CurrentUploadDeferred = $.Deferred();
          _this.div.find("#upload_image").button('loading');
          _this.CurrentUploadDeferred.always(function() {
            return _this.div.find("#upload_image").button('reset');
          });
          return _this.AddPhoto(new Photo(_this._photos.next_id, file.preview, _this.CurrentUploadDeferred));
        }
      }).on('fileuploaddone', function(e, data) {
        var _ref;
        if (((_ref = data.result) != null ? _ref.error : void 0) != null) {
          A2Cribs.UIManager.Error(data.result.error);
          return _this.CurrentUploadDeferred.reject();
        } else {
          return _this.CurrentUploadDeferred.resolve(data);
        }
      }).on('fileuploadfail', function(e, data) {
        A2Cribs.UIManager.Error("Sorry something went wrong. Please retry photo upload.");
        return _this.CurrentUploadDeferred.reject();
      });
    };

    /*
    	Open
    */

    PhotoPicker.Open = function(image_array) {
      this.Load(image_array);
      this.div.modal('show');
      this.PhotoPickerDeferred = $.Deferred();
      return this.PhotoPickerDeferred.promise();
    };

    /*
    	Load
    	Loads up the images to the photo manager
    	given an image array
    */

    PhotoPicker.Load = function(image_array) {
      var image, key, photo, _i, _len, _ref,
        _this = this;
      this.Reset();
      if ((image_array != null ? image_array.length : void 0) != null) {
        for (_i = 0, _len = image_array.length; _i < _len; _i++) {
          image = image_array[_i];
          this.AddPhoto(new Photo(this._photos.next_id, image));
        }
        _ref = this._photos;
        for (key in _ref) {
          photo = _ref[key];
          if ((photo.IsPrimary != null) && photo.IsPrimary()) {
            $(".image-row").trigger("set_primary", [photo.index]);
          }
        }
      }
      return this.div.find("#finish_photo").unbind('click').click(function() {
        var _ref2;
        if (((_ref2 = _this.CurrentUploadDeferred) != null ? _ref2.state() : void 0) === 'pending') {
          A2Cribs.UIManager.Success("Cribspot is still uploading your images. We'll be done in just a moment.");
        }
        return $.when(_this.CurrentUploadDeferred).then(function(resolved) {
          _this.div.modal('hide');
          return _this.PhotoPickerDeferred.resolve(_this.GetPhotos());
        });
      });
    };

    /*
    	Edit
    	Takes an index into the A2Cribs.Image
    	photo image array and displays it in the
    	main photo section to be edited
    */

    PhotoPicker.Edit = function(index) {
      this.CurrentPreviewImage = index;
      this.div.find("#imageContent0").html(this._photos[index].GetPreview());
      this.div.find("#captionInput").val(this._photos[index].GetCaption());
      return this.div.find("#charactersLeft").html(this.MAX_CAPTION_LENGTH - this._photos[index].GetCaption().length);
    };

    /*
    	Reset
    	Resets the UI for a new image set or to
    	load an existing set of images
    */

    PhotoPicker.Reset = function() {
      this.ResetMainPhoto();
      this.div.find(".image-row").empty();
      this.CurrentPrimaryImage = null;
      return this._photos = {
        size: 0,
        next_id: 0
      };
    };

    /*
    	Make Primary
    	Unsets previous primary image and makes
    	index primary
    */

    PhotoPicker.MakePrimary = function(index) {
      if (this.CurrentPrimaryImage != null) {
        this._photos[this.CurrentPrimaryImage].UnsetPrimary();
      }
      return this._photos[this.CurrentPrimaryImage = index].MakePrimary();
    };

    /*
    	Delete
    	Deletes the photo from both the UI and
    	the backend if the photo has been saved
    */

    PhotoPicker.Delete = function(index) {
      var photo,
        _this = this;
      photo = this._photos[index];
      this.RemovePhoto(index);
      $.when(photo.DeleteDeferred).done(function(response) {
        if (!(response != null) || (response.error != null)) {
          A2Cribs.UIManager.Error("Photo could not be deleted!");
          return _this.AddPhoto(photo);
        } else {
          return A2Cribs.UIManager.Success("Photo was successfully deleted!");
        }
      }).fail(function(response) {
        A2Cribs.UIManager.Error("Photo could not be deleted!");
        return _this.AddPhoto(photo);
      });
      if (this.CurrentPrimaryImage === index) return this.DefaultPrimaryPhoto();
    };

    /*
    	Add Photo
    	Pushes photo onto the photo array and
    	renders the preview for the photo box
    */

    PhotoPicker.AddPhoto = function(photo) {
      this._photos[this._photos.next_id] = photo;
      this._photos.size += 1;
      this._photos.next_id += 1;
      this.div.find(".image-row").append(photo.GetHtml());
      if (!(this.CurrentPrimaryImage != null)) return this.DefaultPrimaryPhoto();
    };

    /*
    	Remove Photo
    	Removes photo from the photo array and
    	updates the UI to show there is no more
    	photo
    */

    PhotoPicker.RemovePhoto = function(index) {
      this._photos.size -= 1;
      if (this.CurrentPreviewImage === index) {
        this.div.find("#imageContent0").html("<div class = 'img-place-holder'></div>");
        this.div.find("#captionInput").val("");
        this.div.find("#charactersLeft").text(this.MAX_CAPTION_LENGTH);
      }
      delete this._photos[index];
      return this.div.find("#prev_" + index).fadeOut();
    };

    /*
    	Reset Main Photo
    	Clears the UI for the main photo
    */

    PhotoPicker.ResetMainPhoto = function() {
      this.div.find("#imageContent0").html("<div class = 'img-place-holder'></div>");
      this.div.find("#captionInput").val("");
      this.div.find("#charactersLeft").text(this.MAX_CAPTION_LENGTH);
      return this.CurrentPreviewImage = null;
    };

    /*
    	Default Primary Photo
    	Defaults the current primary image to the first
    	Photo in _photos
    */

    PhotoPicker.DefaultPrimaryPhoto = function() {
      var key, photo, _ref, _results;
      this.CurrentPrimaryImage = null;
      _ref = this._photos;
      _results = [];
      for (key in _ref) {
        photo = _ref[key];
        if (photo.MakePrimary != null) {
          this.CurrentPrimaryImage = parseInt(key, 10);
          photo.MakePrimary();
          break;
        } else {
          _results.push(void 0);
        }
      }
      return _results;
    };

    /*
    	Get Photos
    	Returns an array of all the images that are in the
    	photo picker
    */

    PhotoPicker.GetPhotos = function() {
      var key, photo, results, _ref;
      results = [];
      _ref = this._photos;
      for (key in _ref) {
        photo = _ref[key];
        if (photo.GetObject != null) results.push(photo.GetObject());
      }
      return results;
    };

    /*
    	Document ready
    	Waits for the document to be loaded.
    	When loaded creates all the listeners
    	needed to connect the UI
    */

    $(document).ready(function() {
      if ($("#picture-modal").length) {
        return PhotoPicker.SetupUI($("#picture-modal"));
      }
    });

    return PhotoPicker;

  }).call(this);

}).call(this);
