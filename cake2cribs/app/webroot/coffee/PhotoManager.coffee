class A2Cribs.PhotoManager
	@CurrentPhotoTarget = "none"
	@CurrentPrimaryImageIndex = 1
	@CurrentPreviewImageIndex = 0
	@CurrentPreviewId = 0
	@IdToPathMap = []
	@IdToCaptionMap = []
	@NextImageSlot = 0
	@MAX_CAPTION_LENGTH = 25
	@BACKSPACE = 8

	@LoadImages:() ->
		$.ajax
			url: myBaseUrl + "Images/LoadImages/" + jsVars.edit_listing_id
			type: "GET"
			success: A2Cribs.PhotoManager.UpdateImageSources

	@DeleteImageCallback: (id) ->
		$('#imageContent' + id).html('');
		$('#imageContent' + id).css('background-image', '');
		#$("#" + id).css("visibility", "hidden")
		#$("#add" + id).css("visibility", "visible")
		if id == A2Cribs.PhotoManager.CurrentPreviewId
			$('#imageContent0').html('');
			$('#imageContent0').css('background-image', '');

	@DeleteImage: (obj) ->
		photoNumber = parseInt(obj.id.substring(obj.id.length-1))
		if photoNumber == A2Cribs.PhotoManager.CurrentPrimaryImageIndex
			A2Cribs.PhotoManager.MakeNotPrimaryUI photoNumber
		A2Cribs.PhotoManager.ApplyRemovePhotoUI photoNumber

		$.ajax
			url: myBaseUrl + "Images/DeleteImage"
			type: "GET"
			data: "listing_id=" + jsVars.edit_listing_id + "&image_slot=" + photoNumber
			success: A2Cribs.PhotoManager.DeleteImageCallback(photoNumber)

	@ConfirmAddImage: (obj) ->
		$("#EditPrimaryForm").submit()

	@ConfirmAddImageCallback: (response) ->
		alert response

	@UpdateImageSources: (imageSources) ->
		imageSources = JSON.parse imageSources
		primary_image_index = 0

		if imageSources[0] != null
			primary_image_index = imageSources[0]
			A2Cribs.PhotoManager.CurrentPrimaryImageIndex = primary_image_index
			
			#$("#add1").css("visibility", "hidden")
		#else
			#$("#1").css("visibility", "hidden")
			#$("#add1").css("visibility", "visibile")

		for i in [0..imageSources[1].length - 1] by 1
			if imageSources[1][i] == null || imageSources[1][i] == undefined
				continue
			cssSettings = 
				"background-size":  "160px 150px"
				"background-image": "url(" + imageSources[1][i] + ")"
			imageContentDiv = "#imageContent"
			nextSlot = i + 1
			A2Cribs.PhotoManager.IdToPathMap[nextSlot] = imageSources[1][i]
			if i < imageSources[2].length
				A2Cribs.PhotoManager.IdToCaptionMap[nextSlot] = imageSources[2][i]
			else
				A2Cribs.PhotoManager.IdToCaptionMap[nextSlot] = ""

			A2Cribs.PhotoManager.ApplyAddPhotoUI nextSlot
			imageContentDiv = "#imageContent" + (nextSlot)
			$(imageContentDiv).html("")
			$(imageContentDiv).css(cssSettings)
			if nextSlot == A2Cribs.PhotoManager.CurrentPrimaryImageIndex
				A2Cribs.PhotoManager.MakePrimaryUI primary_image_index
			#$("#add" + (i + 2)).css("visibility", "hidden")
			#$("#" + (i + 2)).css("visibility", "visible")

	@PreviewImage: (obj)->
		file = $("#" + obj.id)[0]
		if obj.id == "0"
			A2Cribs.PhotoManager.CurrentPhotoTarget = "previewDiv"
		else
			A2Cribs.PhotoManager.CurrentPhotoTarget = "secondary"
		if file.files
			file = file.files[0]
			if !A2Cribs.PhotoManager.IsAcceptableFileType(file.name)
				return
			fr = new FileReader
			fr.onloadend = A2Cribs.PhotoManager.SetImage
			fr.readAsDataURL(file)
		else
			file = file.value
			if !A2Cribs.PhotoManager.IsAcceptableFileType(file)
				return
			A2Cribs.PhotoManager.SetImage(file)

	@SetImage: (img) ->
		if typeof img == "object" 
			img = img.target.result; # file reader
		cssSettings = 
			"background-size":  "160px 150px"
			"background-image": "url(" + img + ")"
		imageContentDiv = ""
		if A2Cribs.PhotoManager.CurrentPhotoTarget == "secondary"
			imageContentDiv = A2Cribs.PhotoManager.FindNextFreeDiv()
			num = imageContentDiv.substring(imageContentDiv.length-1)
			A2Cribs.PhotoManager.IdToPathMap[num] = img
			if !imageContentDiv
				alert "You have already uploaded a maximum of 6 images. Please delete an image before uploading another."
				return
			A2Cribs.PhotoManager.ApplyAddPhotoUI num
		else
			imageContentDiv = "#imageContent0"
			cssSettings = 
				"background-size":  "271px 280px"
				"background-image": "url(" + img + ")"
		$(imageContentDiv).html("")
		$(imageContentDiv).css(cssSettings)

	###
	Find the next free div in which to display the selected photo
	###
	@FindNextFreeDiv: ->
		imageDivPrefix = "#imageContent"
		foundFreeDiv = false
		freeDivId = 0
		for i in [1..6] by 1
				candidateDiv = imageDivPrefix + i
				backgroundImg = $(candidateDiv).css("background-image")
				if backgroundImg == "none" || backgroundImg == undefined
					imageContentDiv = candidateDiv
					foundFreeDiv = true
					#A2Cribs.PhotoManager.IdToPathMap[i] = img
					freeDivId = i
					A2Cribs.PhotoManager.NextImageSlot = freeDivId
					break
		if foundFreeDiv
			return imageContentDiv
		else
			return false

	@ShowRequest: (formData, jqForm, options) ->
		alert formData
		###fileToUploadValue = $('input[@name=fileToUpload]').fieldValue()
		if !fileToUploadValue[0]
			return false###
		
		return true

	@ShowResponse: (data, statusText) ->
		alert data

	@EditImage: (obj) ->
		photoNumber = parseInt(obj.id.substring(obj.id.length-1))
		A2Cribs.PhotoManager.CurrentPhotoTarget = "previewDiv"
		A2Cribs.PhotoManager.CurrentPreviewId = photoNumber
		img = A2Cribs.PhotoManager.IdToPathMap[photoNumber]
		A2Cribs.PhotoManager.SetImage img
		A2Cribs.PhotoManager.CurrentPreviewImageIndex = photoNumber
		$("#captionInput").val(A2Cribs.PhotoManager.IdToCaptionMap[photoNumber])

	@CaptionKeyUp: ->
		curString = $("#captionInput").val()
		if curString.length == A2Cribs.PhotoManager.MAX_CAPTION_LENGTH
			$("#charactersLeft").html("0")
			$("#charactersLeft").css("color", "red")

		else
			$("#charactersLeft").html(A2Cribs.PhotoManager.MAX_CAPTION_LENGTH - curString.length)
			$("#charactersLeft").css("color", "black")

	@IsAcceptableFileType: (fileName) ->
		indexOfDot = fileName.indexOf ".", fileName.length - 4
		if indexOfDot == -1
			return false

		fileType = fileName.substring(indexOfDot + 1)
		if fileType == "jpg" || fileType == "jpeg" || fileType == "png"
			return true

		alert "Not a valid file type. Valid file types include 'jpg', jpeg', or 'png'."
		return false
	###
	if statusText == 'success'
		if data.img != ''
			document.getElementById('result').innerHTML = '<img src="/upload/thumb/'+data.img+'" />';
			document.getElementById('message').innerHTML = data.error;
		else
			document.getElementById('message').innerHTML = data.error;
	else
		document.getElementById('message').innerHTML = 'Unknown error!';###

	@MakePrimary: (obj) ->
		photoNumber = parseInt(obj.id.substring(obj.id.length-1))
		img = $("#imageContent" + photoNumber).css("background-image")
		if img != "none" && img != undefined 
			A2Cribs.PhotoManager.MakeNotPrimaryUI A2Cribs.PhotoManager.CurrentPrimaryImageIndex
			A2Cribs.PhotoManager.MakePrimaryUI photoNumber
			A2Cribs.PhotoManager.CurrentPrimaryImageIndex = photoNumber
			$.ajax
				url: myBaseUrl + "Images/MakePrimary/" + photoNumber 
				type: "GET"

	###
	Update UI for image that is now primary
	###
	@MakePrimaryUI: (divId) ->
		$("#primary" + divId).css("background-color", "yellow")
		$("#primary" + divId).attr("disabled", "disabled")

	###
	Update UI for image that is no longer primary
	###
	@MakeNotPrimaryUI: (divId) ->
		$("#primary" + divId).css("background-color", "gray")
		$("#primary" + divId).removeAttr("disabled")

	###
	Submit the caption for the currently previewed image.
	###
	@SubmitCaption: ->
		caption = $("#captionInput").val()
		ind = A2Cribs.PhotoManager.CurrentPreviewImageIndex
		#TODO: need to set IdToCaptionMap in callback to ensure that caption was accepted
		$.ajax
			url: myBaseUrl + "Images/SubmitCaption/" + caption + "/" +  ind
			type: "GET"
			success: A2Cribs.PhotoManager.SubmitCaptionCallback

	@SubmitCaptionCallback: (response) ->
		if response == "SUCCESS"
			A2Cribs.PhotoManager.IdToCaptionMap[A2Cribs.PhotoManager.CurrentPreviewImageIndex] = $("#captionInput").val()
		else
			alert "Error: Please use only numbers and letters."

	###
	Update visibility of buttons for image after added to slot imageSlot
	###
	@ApplyAddPhotoUI: (imageSlot) ->
		$("#delete" + imageSlot).toggleClass("hide")
		$("#primary" + imageSlot).toggleClass("hide")
		$("#edit" + imageSlot).toggleClass("hide")

	###
	Update visibility of buttons for image after being removed
	###
	@ApplyRemovePhotoUI: (imageSlot) ->
		$("#delete" + imageSlot).toggleClass("hide")
		$("#primary" + imageSlot).toggleClass("hide")
		$("#edit" + imageSlot).toggleClass("hide")