class A2Cribs.PhotoManager
	class Photo
		constructor: (@_div)->
			@_imageId = -1
			@_isEmpty = true
			@_isPrimary = false
			@_caption = ""
			@_path = ""
			@_preview = null

		LoadPhoto: (@_imageId, @_path, @_caption, isPrimary) ->
			@_isEmpty = false
			path = Photo.ProcessImagePath @_path
			@_preview = "<img src='#{path}'></img>"
			@_div.find(".imageContent").html @_preview
			@SetPrimary isPrimary

		CreatePreview: (@_file) ->
			if not Photo.IsAcceptableFileType @_file.name
				return
			@_isEmpty = no
			reader = new FileReader
			reader.onloadend = (img) => 
				if typeof img == "object" 
					img = img.target.result; # file reader
					
				@_preview = "<img src='#{img}'></img>"

				@_div.find(".imageContent").html @_preview

			reader.readAsDataURL @_file

		GetPreview: ->
			@_preview

		SaveCaption: (caption) ->
			@_caption = caption

		GetCaption: ->
			@_caption

		GetImageId: ->
			@_imageId

		IsPrimary: ->
			@_isPrimary

		SetPrimary: (value) ->
			@_isPrimary = value
			if value
				@_div.find(".primary").addClass 'cur-primary'
			else
				@_div.find(".primary").removeClass 'cur-primary'

		SetId: (id) ->
			@_imageId = id

		SetPath: (path) ->
			@_path = path

		SetListingId: (listing_id) ->
			@_listing_id = listing_id

		IsEmpty: ->
			@_isEmpty

		Reset: ->
			@_isEmpty = yes
			@_div.find(".imageContent").html '<div class="img-place-holder"></div>'
			@_div.find(".image-actions-container").hide()
			@_isPrimary = no
			@_caption = ""
			@_path = ""
			@_preview = null

		GetObject: ->
				image_id: @_imageId
				caption: @_caption
				is_primary: +@_isPrimary
				image_path: @_path
				listing_id: @_listing_id

		###
		Prepends 'med_' to the filename and returns result
		###
		@ProcessImagePath: (path) ->
			directory = path.substr(0, path.lastIndexOf '/')
			filename = 'med_' + path.substr(path.lastIndexOf('/') + 1)
			return directory + '/' + filename

		@IsAcceptableFileType: (fileName) ->
			indexOfDot = fileName.indexOf ".", fileName.length - 4
			if indexOfDot == -1
				return false

			fileType = fileName.substring(indexOfDot + 1).toLowerCase()
			if fileType == "jpg" || fileType == "jpeg" || fileType == "png"
				return true

			A2Cribs.UIManager.Alert "Not a valid file type. Valid file types include 'jpg', jpeg', or 'png'."
			return false

	@NUM_PREVIEWS = 6

	constructor: (div) ->
		@div = div
		@SetupUI()

		@CurrentPrimaryImage = 0
		@CurrentPreviewImage = null
		@CurrentImageLoading = null
		@Photos = [];
		@div.find(".imageContainer").each (index, div) =>
			@Photos.push new Photo $(div)

		@MAX_CAPTION_LENGTH = 25
		@BACKSPACE = 8

	UploadImageDefer: () ->
		@UploadCompleteDeferred = new $.Deferred()
		@UploadCompleteDeferred.promise()

	SetupUI:() ->
		that = @
		@div.find('.imageContainer').hover (event) =>
			if $(event.currentTarget).find('img').length == 1
				if event.type == 'mouseenter'
					$(event.currentTarget).find('.image-actions-container').show()
				else
					$(event.currentTarget).find('.image-actions-container').hide()

		@div.find('#upload_image').click () =>
			@div.find('#real-file-input').click()

		@div.find(".imageContent").click (event) =>
			index = +event.currentTarget.id.match(/\d+/g)[0]
			@EditImage index - 1

		@div.find(".edit").click (event) =>
			index = +event.currentTarget.id.match(/\d+/g)[0]
			@EditImage index - 1

		@div.find(".delete").click (event) =>
			index = +event.currentTarget.id.match(/\d+/g)[0]
			@DeleteImage index - 1			

		@div.find(".primary").click (event) =>
			index = +event.currentTarget.id.match(/\d+/g)[0]
			@MakePrimary index - 1

		@div.find("#captionInput").keyup () =>
			curString = @div.find("#captionInput").val()
			if curString.length == @MAX_CAPTION_LENGTH
				@div.find("#charactersLeft").css("color", "red")
			else
				@div.find("#charactersLeft").css("color", "black")

			@div.find("#charactersLeft").html(@MAX_CAPTION_LENGTH - curString.length)
			if @CurrentPreviewImage?
				@Photos[@CurrentPreviewImage].SaveCaption @div.find("#captionInput").val()


		@div.find(".delete").tooltip {'selector': '','placement': 'bottom', 'title': 'Delete'}
		@div.find(".edit").tooltip {'selector': '','placement': 'bottom', 'title': 'Edit'}
		@div.find(".primary").tooltip {'selector': '','placement': 'bottom', 'title': 'Make Primary'}

		max_file_size = 5000000 # 5 MB

		@div.find('#ImageAddForm').fileupload
			url: myBaseUrl + 'images/AddImage'
			dataType: 'json'
			acceptFileTypes: /(\.|\/)(jpeg|jpg|png)$/i
			singleFileUploads: true
			maxFileSize: max_file_size
			loadImageMaxFileSize: max_file_size
			disableImageResize: false,
			previewMaxWidth: 100
			previewMaxHeight: 100
			previewCrop: true
		.on 'fileuploadsend', (e, data) =>
			if data.files[0].type.indexOf("image") is -1
				A2Cribs.UIManager.Error "Sorry - Please only upload png, jpeg or jpg!"
				return false
			if data.files[0].size > max_file_size
				A2Cribs.UIManager.Error "Sorry - the image you uploaded was too large!"
				return false
			if @NextAvailablePhoto() == -1
				A2Cribs.UIManager.Error "Sorry - you can't upload more than 6 photos at this time."
				return false
			@UploadImageDefer()
			@div.find("#upload_image").button 'loading'
			if (@CurrentImageLoading = @NextAvailablePhoto()) >= 0 and data.files? and data.files[0]?
				@Photos[@CurrentImageLoading].CreatePreview data.files[0], 
					@div.find "#imageContent" + (@CurrentImageLoading + 1)
			return true
		.on 'fileuploaddone', (e, data) =>
			# Now listing save can proceed, resolve deferred object with either true or false

			@div.find("#upload_image").button 'reset'
			if data.result.errors? and data.result.errors.length
				A2Cribs.UIManager.Error "Failed to upload image!"
				@UploadCompleteDeferred.resolve(null)
				@Photos[@CurrentImageLoading].Reset()
			else
				@Photos[@CurrentImageLoading].SetId data.result.image_id
				@Photos[@CurrentImageLoading].SetPath data.result.image_path
				if @PhotoCount() is 1
					@MakePrimary @CurrentImageLoading
				@UploadCompleteDeferred.resolve(true)
		.on 'fileuploadfail', (e, data) =>
			if @CurrentImageLoading
				A2Cribs.UIManager.Error "Failed to upload image!"
				@div.find("#upload_image").button 'reset'
				@Photos[@CurrentImageLoading].Reset()

	Open: (image_array, imageCallback, row = null) ->
		@LoadImages(image_array, imageCallback, row)
		@div.modal('show')

	LoadImages: (image_array, imageCallback, row = null) ->
		@Reset()
		if image_array?.length?
			for image in image_array
				@Photos[@NextAvailablePhoto()].LoadPhoto image.image_id, image.image_path, image.caption ,image.is_primary

		@div.find("#finish_photo").unbind 'click'
		@div.find("#finish_photo").click () =>
			if @UploadCompleteDeferred.state() is 'pending'
				A2Cribs.UIManager.Success "Cribspot is still uploading your images. We'll be done in just a moment."	
			###
			FIXING GITHUB ISSUE 141
			Need to wait until image save is complete before attempting to save row, or data isn't in cache yet
			###
			$.when(@UploadCompleteDeferred).then (resolved) =>
				imageCallback @GetPhotos(), row
				@div.modal('hide')
				return

	NextAvailablePhoto: ->
		for photo, i in @Photos
			if photo.IsEmpty()
				return i
		return -1

	PhotoCount: ->
		count = 0
		for photo, i in @Photos
			if not photo.IsEmpty()
				count += 1
		return count

	DeleteImage: (index) ->
		$.ajax
			url: myBaseUrl + "images/delete/" + @Photos[index].GetImageId()
			type: "GET"
			success: =>
				@Photos[index].Reset()
				if index is @CurrentPrimaryImage
					for photo, i in @Photos
						if not photo.IsEmpty()
							@MakePrimary i
				if index is @CurrentPreviewImage
					@div.find("#imageContent0").html '<div class="img-place-holder"></div>'

	EditImage: (index) ->
		if not @Photos[index].IsEmpty()
			@CurrentPreviewImage = index
			@div.find("#imageContent0").html @Photos[index].GetPreview()
			@div.find("#captionInput").val @Photos[index].GetCaption()
			@div.find("#charactersLeft").html @MAX_CAPTION_LENGTH - @Photos[index].GetCaption().length

	MakePrimary: (index) ->
		@Photos[@CurrentPrimaryImage].SetPrimary false
		@Photos[@CurrentPrimaryImage = index].SetPrimary true

	Reset: ->
		@div.find("#imageContent0").html '<div class="img-place-holder"></div>'
		@div.find("#captionInput").val ""
		@div.find("#charactersLeft").html @MAX_CAPTION_LENGTH
		@UploadCompleteDeferred = new $.Deferred().resolve true

		for photo in @Photos
			photo.Reset()

	GetPhotos: ->
		results = []
		for photo in @Photos
			if not photo.IsEmpty()
				results.push photo.GetObject()

		if results.length is 0 then null else results

	###
	Send photo and photo's row_id to server
	The form of this function is mostly for testing the backend handling of row_id.
	###
	@SubmitPhoto: (row_id, photo) -> 
		$.ajax
			url: myBaseUrl + "images/AddImage/" + row_id + "/" + photo
			type: "POST"
			success: (response) =>
				response = JSON.parse response
				console.log response


	###
	Picture Modal On Ready
	Waits for the picture modal element to be loaded
	before initializing the PhotoManager
	###
	$(document).ready =>
		if $("#picture-modal").length
			A2Cribs.PhotoManager = new A2Cribs.PhotoManager($("#picture-modal"))

	
