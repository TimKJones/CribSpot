class A2Cribs.PhotoManager
	@CurrentPhotoTarget = "none"
	@CurrentPreviewId = 0
	@IdToPathMap = []
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
		$("#" + id).css("visibility", "hidden")
		$("#add" + id).css("visibility", "visible")
		if id == A2Cribs.PhotoManager.CurrentPreviewId
			$('#imageContent0').html('');
			$('#imageContent0').css('background-image', '');

	@DeleteImage: (obj) ->
		photoNumber = parseInt(obj.id.substring(obj.id.length-1))
		$.ajax
			url: myBaseUrl + "Images/DeleteImage"
			type: "GET"
			data: "listing_id=" + jsVars.edit_listing_id + "&path=" + A2Cribs.PhotoManager.IdToPathMap[photoNumber]
			success: A2Cribs.PhotoManager.DeleteImageCallback(photoNumber)

	@ConfirmAddImage: (obj) ->
		$("#EditPrimaryForm").submit()

	@ConfirmAddImageCallback: (response) ->
		alert response

	@UpdateImageSources: (imageSources) ->
		imageSources = JSON.parse imageSources
		for i in [0..imageSources[1].length - 1] by 1
			A2Cribs.PhotoManager.IdToPathMap[0] = imageSources[0]

		if imageSources[0] != null
			A2Cribs.PhotoManager.IdToPathMap[0] = imageSources[0]
			cssSettings = 
				"background-size":  "256px 256px"
				"background-image": "url(" + imageSources[0] + ")"
			imageContentDiv = "#imageContent1"
			$(imageContentDiv).html("")
			$(imageContentDiv).css(cssSettings)
			$("#add1").css("visibility", "hidden")
			$("#1").css("visibility", "visible")
		else
			$("#1").css("visibility", "hidden")
			$("#add1").css("visibility", "visibile")

		for i in [0..imageSources[1].length - 1] by 1
			if imageSources[1][i] == null || imageSources[1][i] == undefined
				continue
			A2Cribs.PhotoManager.IdToPathMap[i+1] = imageSources[1][i]
			cssSettings = 
				"background-size":  "256px 256px"
				"background-image": "url(" + imageSources[1][i] + ")"
			imageContentDiv = "#imageContent" + (i + 2)
			$(imageContentDiv).html("")
			$(imageContentDiv).css(cssSettings)
			$("#add" + (i + 2)).css("visibility", "hidden")
			$("#" + (i + 2)).css("visibility", "visible")

	@SubmitPhoto: ->
		$("#ImageAddForm").submit()

	@PreviewImage: (obj)->
		file = $("#" + obj.id)[0]
		if obj.id == "0"
			A2Cribs.PhotoManager.CurrentPhotoTarget = "previewDiv"
		else
			A2Cribs.PhotoManager.CurrentPhotoTarget = "secondary"
		if file.files
			file = file.files[0]
			fr = new FileReader
			A2Cribs.PhotoManager.CurrentFileNumber = parseInt obj.id.substring(obj.id.length - 1)
			fr.onloadend = A2Cribs.PhotoManager.SetImage
			fr.readAsDataURL(file)
		else 
			file = file.value
			A2Cribs.PhotoManager.SetImage(file)

	@SetImage: (img) ->
		if typeof img == "object" 
			img = img.target.result; # file reader
		cssSettings = 
			"background-size":  "160px 150px"
			"background-image": "url(" + img + ")"
		imageContentDiv = ""
		imageDivPrefix = "#imageContent"
		foundFreeDiv = false
		freeDivId = 0
		if A2Cribs.PhotoManager.CurrentPhotoTarget == "secondary"
			for i in [1..6] by 1
				candidateDiv = imageDivPrefix + i
				backgroundImg = $(candidateDiv).css("background-image")
				if backgroundImg == "none" || backgroundImg == undefined
					imageContentDiv = candidateDiv
					foundFreeDiv = true
					A2Cribs.PhotoManager.IdToPathMap[i] = img
					freeDivId = i
					break
			if !foundFreeDiv
				alert "You have already uploaded a maximum of 6 images. Please delete an image before uploading another."
				return
		else
			imageContentDiv = "#imageContent0"
			cssSettings = 
				"background-size":  "271px 280px"
				"background-image": "url(" + img + ")"
		$(imageContentDiv).html("")
		$(imageContentDiv).css(cssSettings)


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
		A2Cribs.PhotoManager.SetImage(A2Cribs.PhotoManager.IdToPathMap[photoNumber])

	@CaptionKeyUp: ->
		curString = $("#captionInput").val()
		if curString.length == A2Cribs.PhotoManager.MAX_CAPTION_LENGTH
			$("#charactersLeft").html("0")
			$("#charactersLeft").css("color", "red")

		else
			$("#charactersLeft").html(A2Cribs.PhotoManager.MAX_CAPTION_LENGTH - curString.length)
			$("#charactersLeft").css("color", "black")

	@SubmitCaption: ->
		caption = $("#captionInput").val()
		alert "submitting " + caption
	###
	if statusText == 'success'
		if data.img != ''
			document.getElementById('result').innerHTML = '<img src="/upload/thumb/'+data.img+'" />';
			document.getElementById('message').innerHTML = data.error;
		else
			document.getElementById('message').innerHTML = data.error;
	else
		document.getElementById('message').innerHTML = 'Unknown error!';###

