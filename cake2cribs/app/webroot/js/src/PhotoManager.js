(function() {

  A2Cribs.PhotoManager = (function() {

    function PhotoManager() {}

    PhotoManager.CurrentPhotoTarget = "none";

    PhotoManager.CurrentPrimaryImageIndex = 1;

    PhotoManager.CurrentPreviewImageIndex = 0;

    PhotoManager.CurrentPreviewId = 0;

    PhotoManager.IdToPathMap = [];

    PhotoManager.IdToCaptionMap = [];

    PhotoManager.NextImageSlot = 0;

    PhotoManager.MAX_CAPTION_LENGTH = 25;

    PhotoManager.BACKSPACE = 8;

    PhotoManager.LoadImages = function() {
      return $.ajax({
        url: myBaseUrl + "Images/LoadImages/" + jsVars.edit_listing_id,
        type: "GET",
        success: A2Cribs.PhotoManager.UpdateImageSources
      });
    };

    PhotoManager.DeleteImageCallback = function(id) {
      $('#imageContent' + id).html('');
      $('#imageContent' + id).css('background-image', '');
      if (id === A2Cribs.PhotoManager.CurrentPreviewId) {
        $('#imageContent0').html('');
        return $('#imageContent0').css('background-image', '');
      }
    };

    PhotoManager.DeleteImage = function(obj) {
      var photoNumber;
      photoNumber = parseInt(obj.id.substring(obj.id.length - 1));
      if (photoNumber === A2Cribs.PhotoManager.CurrentPrimaryImageIndex) {
        A2Cribs.PhotoManager.MakeNotPrimaryUI(photoNumber);
      }
      A2Cribs.PhotoManager.ApplyRemovePhotoUI(photoNumber);
      return $.ajax({
        url: myBaseUrl + "Images/DeleteImage",
        type: "GET",
        data: "listing_id=" + jsVars.edit_listing_id + "&image_slot=" + photoNumber,
        success: A2Cribs.PhotoManager.DeleteImageCallback(photoNumber)
      });
    };

    PhotoManager.ConfirmAddImage = function(obj) {
      return $("#EditPrimaryForm").submit();
    };

    PhotoManager.ConfirmAddImageCallback = function(response) {
      return alert(response);
    };

    PhotoManager.UpdateImageSources = function(imageSources) {
      var cssSettings, i, imageContentDiv, nextSlot, primary_image_index, _ref, _results;
      imageSources = JSON.parse(imageSources);
      primary_image_index = 0;
      A2Cribs.PhotoManager.IdToPathMap = [];
      A2Cribs.PhotoManager.IdToCaptionMap = [];
      if (imageSources[0] !== null) {
        primary_image_index = imageSources[0];
        A2Cribs.PhotoManager.CurrentPrimaryImageIndex = primary_image_index;
      }
      _results = [];
      for (i = 0, _ref = imageSources[1].length - 1; i <= _ref; i += 1) {
        if (imageSources[1][i] === null || imageSources[1][i] === void 0) continue;
        cssSettings = {
          "background-size": "160px 150px",
          "background-image": "url(" + imageSources[1][i] + ")"
        };
        imageContentDiv = "#imageContent";
        nextSlot = i + 1;
        A2Cribs.PhotoManager.IdToPathMap[nextSlot] = imageSources[1][i];
        if (i < imageSources[2].length) {
          A2Cribs.PhotoManager.IdToCaptionMap[nextSlot] = imageSources[2][i];
        } else {
          A2Cribs.PhotoManager.IdToCaptionMap[nextSlot] = "";
        }
        A2Cribs.PhotoManager.ApplyAddPhotoUI(nextSlot);
        imageContentDiv = "#imageContent" + nextSlot;
        $(imageContentDiv).html("");
        $(imageContentDiv).css(cssSettings);
        if (nextSlot === A2Cribs.PhotoManager.CurrentPrimaryImageIndex) {
          _results.push(A2Cribs.PhotoManager.MakePrimaryUI(primary_image_index));
        } else {
          _results.push(void 0);
        }
      }
      return _results;
    };

    PhotoManager.PreviewImage = function(obj) {
      var file, fr;
      $('#ImageAddForm').fileupload({
        singleFileUploads: true,
        url: "/images/add"
      });
      file = $("#" + obj.id)[0];
      if (obj.id === "0") {
        A2Cribs.PhotoManager.CurrentPhotoTarget = "previewDiv";
      } else {
        A2Cribs.PhotoManager.CurrentPhotoTarget = "secondary";
      }
      if (file.files) {
        file = file.files[0];
        if (!A2Cribs.PhotoManager.IsAcceptableFileType(file.name)) return;
        fr = new FileReader;
        fr.onloadend = A2Cribs.PhotoManager.SetImage;
        return fr.readAsDataURL(file);
      } else {
        file = file.value;
        if (!A2Cribs.PhotoManager.IsAcceptableFileType(file)) return;
        return A2Cribs.PhotoManager.SetImage(file);
      }
    };

    PhotoManager.SetImage = function(img) {
      var cssSettings, imageContentDiv, num;
      if (typeof img === "object") img = img.target.result;
      cssSettings = {
        "background-size": "160px 150px",
        "background-image": "url(" + img + ")"
      };
      imageContentDiv = "";
      if (A2Cribs.PhotoManager.CurrentPhotoTarget === "secondary") {
        imageContentDiv = A2Cribs.PhotoManager.FindNextFreeDiv();
        num = imageContentDiv.substring(imageContentDiv.length - 1);
        A2Cribs.PhotoManager.IdToPathMap[num] = img;
        if (!imageContentDiv) {
          alert("You have already uploaded a maximum of 6 images. Please delete an image before uploading another.");
          return;
        }
        A2Cribs.PhotoManager.ApplyAddPhotoUI(num);
      } else {
        imageContentDiv = "#imageContent0";
        cssSettings = {
          "background-size": "271px 280px",
          "background-image": "url(" + img + ")"
        };
      }
      $(imageContentDiv).html("");
      return $(imageContentDiv).css(cssSettings);
    };

    /*
    	Find the next free div in which to display the selected photo
    */

    PhotoManager.FindNextFreeDiv = function() {
      var backgroundImg, candidateDiv, foundFreeDiv, freeDivId, i, imageContentDiv, imageDivPrefix;
      imageDivPrefix = "#imageContent";
      foundFreeDiv = false;
      freeDivId = 0;
      for (i = 1; i <= 6; i += 1) {
        candidateDiv = imageDivPrefix + i;
        backgroundImg = $(candidateDiv).css("background-image");
        if (backgroundImg === "none" || backgroundImg === void 0) {
          imageContentDiv = candidateDiv;
          foundFreeDiv = true;
          freeDivId = i;
          A2Cribs.PhotoManager.NextImageSlot = freeDivId;
          break;
        }
      }
      if (foundFreeDiv) {
        return imageContentDiv;
      } else {
        return false;
      }
    };

    PhotoManager.ShowRequest = function(formData, jqForm, options) {
      alert(formData);
      /*fileToUploadValue = $('input[@name=fileToUpload]').fieldValue()
      		if !fileToUploadValue[0]
      			return false
      */
      return true;
    };

    PhotoManager.ShowResponse = function(data, statusText) {
      return alert(data);
    };

    PhotoManager.EditImage = function(obj) {
      var img, old, photoNumber;
      photoNumber = parseInt(obj.id.substring(obj.id.length - 1));
      A2Cribs.PhotoManager.CurrentPhotoTarget = "previewDiv";
      A2Cribs.PhotoManager.CurrentPreviewId = photoNumber;
      img = A2Cribs.PhotoManager.IdToPathMap[photoNumber];
      A2Cribs.PhotoManager.SetImage(img);
      old = A2Cribs.PhotoManager.CurrentPreviewImageIndex;
      A2Cribs.PhotoManager.CurrentPreviewImageIndex = photoNumber;
      $("#captionInput").val(A2Cribs.PhotoManager.IdToCaptionMap[photoNumber]);
      $("#imageContainer" + photoNumber).removeClass("unselected");
      $("#imageContainer" + photoNumber).addClass("selected");
      $("#imageContainer" + old).removeClass("selected");
      return $("#imageContainer" + old).addClass("unselected");
    };

    PhotoManager.CaptionKeyUp = function() {
      var curString;
      curString = $("#captionInput").val();
      if (curString.length === A2Cribs.PhotoManager.MAX_CAPTION_LENGTH) {
        $("#charactersLeft").html("0");
        return $("#charactersLeft").css("color", "red");
      } else {
        $("#charactersLeft").html(A2Cribs.PhotoManager.MAX_CAPTION_LENGTH - curString.length);
        return $("#charactersLeft").css("color", "black");
      }
    };

    PhotoManager.IsAcceptableFileType = function(fileName) {
      var fileType, indexOfDot;
      indexOfDot = fileName.indexOf(".", fileName.length - 4);
      if (indexOfDot === -1) return false;
      fileType = fileName.substring(indexOfDot + 1);
      if (fileType === "jpg" || fileType === "jpeg" || fileType === "png") {
        return true;
      }
      alert("Not a valid file type. Valid file types include 'jpg', jpeg', or 'png'.");
      return false;
    };

    /*
    	if statusText == 'success'
    		if data.img != ''
    			document.getElementById('result').innerHTML = '<img src="/upload/thumb/'+data.img+'" />';
    			document.getElementById('message').innerHTML = data.error;
    		else
    			document.getElementById('message').innerHTML = data.error;
    	else
    		document.getElementById('message').innerHTML = 'Unknown error!';
    */

    PhotoManager.MakePrimary = function(obj) {
      var img, photoNumber;
      photoNumber = parseInt(obj.id.substring(obj.id.length - 1));
      img = $("#imageContent" + photoNumber).css("background-image");
      if (img !== "none" && img !== void 0) {
        A2Cribs.PhotoManager.MakeNotPrimaryUI(A2Cribs.PhotoManager.CurrentPrimaryImageIndex);
        A2Cribs.PhotoManager.MakePrimaryUI(photoNumber);
        A2Cribs.PhotoManager.CurrentPrimaryImageIndex = photoNumber;
        return $.ajax({
          url: myBaseUrl + "Images/MakePrimary/" + photoNumber,
          type: "GET"
        });
      }
    };

    /*
    	Update UI for image that is now primary
    */

    PhotoManager.MakePrimaryUI = function(divId) {
      $("#primary" + divId).css("background-color", "yellow");
      return $("#primary" + divId).attr("disabled", "disabled");
    };

    /*
    	Update UI for image that is no longer primary
    */

    PhotoManager.MakeNotPrimaryUI = function(divId) {
      $("#primary" + divId).css("background-color", "gray");
      return $("#primary" + divId).removeAttr("disabled");
    };

    /*
    	Submit the caption for the currently previewed image.
    */

    PhotoManager.SubmitCaption = function() {
      var caption, ind;
      caption = $("#captionInput").val();
      ind = A2Cribs.PhotoManager.CurrentPreviewImageIndex;
      return $.ajax({
        url: myBaseUrl + "Images/SubmitCaption/" + caption + "/" + ind,
        type: "GET",
        success: A2Cribs.PhotoManager.SubmitCaptionCallback
      });
    };

    PhotoManager.SubmitCaptionCallback = function(response) {
      if (response === "SUCCESS") {
        return A2Cribs.PhotoManager.IdToCaptionMap[A2Cribs.PhotoManager.CurrentPreviewImageIndex] = $("#captionInput").val();
      } else {
        return alert("Error: Please use only numbers and letters.");
      }
    };

    /*
    	Update visibility of buttons for image after added to slot imageSlot
    */

    PhotoManager.ApplyAddPhotoUI = function(imageSlot) {
      $("#delete" + imageSlot).toggleClass("hide");
      $("#primary" + imageSlot).toggleClass("hide");
      return $("#edit" + imageSlot).toggleClass("hide");
    };

    /*
    	Update visibility of buttons for image after being removed
    */

    PhotoManager.ApplyRemovePhotoUI = function(imageSlot) {
      $("#delete" + imageSlot).toggleClass("hide");
      $("#primary" + imageSlot).toggleClass("hide");
      return $("#edit" + imageSlot).toggleClass("hide");
    };

    return PhotoManager;

  })();

}).call(this);
