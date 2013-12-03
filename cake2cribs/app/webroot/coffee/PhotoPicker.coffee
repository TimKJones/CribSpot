class A2Cribs.PhotoPicker
	@MAX_CAPTION_LENGTH = 25
	@MAX_PHOTOS = 24
	@MAX_FILE_SIZE =  5000000 # 5 MB

	###
	Class Photo
	Holds all the information for each photo
	also has some methods to make
	###
	class Photo
		###
		Photo Constructor
		Takes integer for the index in the photo array,
		an object that is either a preview for the image
		to be saved or is the complete photo. A deferred
		object is included if the photo has yet to be
		saved.
		###
		constructor: (@index, object, deferred = null) ->
			# Photo has yet to be uploaded
			if deferred?
				@SetPreview object
				deferred
				.done(@Resolve)
			# The object has already been saved
			else
				image_id: @_image_id = object.image_id
				@SaveCaption object.caption
				@_is_primary = object.is_primary
				@_path = object.image_path
				@_listing_id = object.listing_id

		###
		Set Preview
		Sets the preview of the sent object
		###
		SetPreview: (@preview) ->

		###
		Resolve
		When the photo has been saved to the backend,
		the resolve callback is called saving the other
		parts of the image object to this photo
		###
		Resolve: (data) =>
			console.log data.result
			@_image_id = data.result.image_id
			@_path = data.result.image_path
			@_listing_id = data.result.listing_id

		###
		Edit
		Triggers an edit event to make the image
		appear in the edit window
		###
		Edit: ->
			$(".image-row").trigger "edit_image", [@index]

		###
		Delete
		Triggers a delete on the index
		###
		Delete: ->
			$(".image-row").trigger "delete_image", [@index]
			@DeleteDeferred = null
			if @_image_id?
				@DeleteDeferred = $.ajax
					url: myBaseUrl + "images/delete/" + @_image_id
					type: "GET"

		###
		Make Primary
		###
		MakePrimary: ->
			# add class that shows the icon as primary
			# set is_primary to true
			@_is_primary = yes
			@div.find(".primary").addClass 'cur-primary'

		###
		Unset Primary
		###
		UnsetPrimary: ->
			# remove the class that shows the icon as primary
			# set is_primary to false
			@_is_primary = no
			@div.find(".primary").removeClass 'cur-primary'

		IsPrimary: ->
			return if @_is_primary? then @_is_primary else no

		###
		Get Preview
		Returns either a canvas or an image of the photo
		###
		GetPreview: ->
			if @preview?
				newCanvas = document.createElement('canvas')
				context = newCanvas.getContext('2d')
				newCanvas.width = @preview.width
				newCanvas.height = @preview.height
				context.drawImage(@preview, 0, 0)
				return newCanvas
			else
				path_splice = @_path.split "/"
				return $("<img src='/#{path_splice[0]}/#{path_splice[1]}/med_#{path_splice[2]}'>")

		###
		Get Caption
		Returns the caption of the photo
		###
		GetCaption: ->
			return if @_caption? then @_caption else ""

		###
		Save Caption
		Sets the caption of the photo
		###
		SaveCaption: (@_caption) ->

		###
		Get Object
		Returns the necessary fields for A2Cribs.Image
		conversion
		###
		GetObject: ->
			image_id: @_image_id
			caption: @GetCaption()
			is_primary: +@IsPrimary()
			image_path: @_path
			listing_id: @_listing_id

		###
		Get Html
		Returns the html for the image container for 
		this photo
		###
		GetHtml: ->
			if not @div?
				@div = $("""
					<div id="prev_#{@index}" class="imageContainer span6">
						<div class="imageContent imageThumb"></div>
						<div class = 'image-actions-container'>
							<i class="delete icon-trash"></i>
							<i class="edit icon-edit"></i>
							<i class="primary icon-asterisk"></i>
						</div>
					</div>
				""")
				if @preview?
					@div.find(".imageContent").html @preview
				else
					path_splice = @_path.split "/"
					@div.find(".imageContent").html "<img src='/#{path_splice[0]}/#{path_splice[1]}/med_#{path_splice[2]}'>"
				@div.find(".delete").click () =>
					@Delete()
				@div.find(".edit, .imageContent").click () =>
					@Edit()
				@div.find(".primary").click () =>
					$(".image-row").trigger "set_primary", [@index]

				# Set tooltips the helper buttons at the bottom
				@div.find(".delete").tooltip {'selector': '','placement': 'bottom', 'title': 'Delete'}
				@div.find(".edit").tooltip {'selector': '','placement': 'bottom', 'title': 'Edit'}
				@div.find(".primary").tooltip {'selector': '','placement': 'bottom', 'title': 'Make Primary'}
			return @div


	###
	SetupUI
	Attaches listeners to all the UI elements
	in the photo manager
	###
	@SetupUI: (@div) ->
		@Reset()

		# Listens for a delete event from a Photo
		@div.find(".image-row").on "delete_image", (event, index) =>
			@Delete index

		# Listens for edit event from a Photo
		@div.find(".image-row").on "edit_image", (event, index) =>
			@Edit index

		# Listens for make primary event from a Photo
		@div.find(".image-row").on "set_primary", (event, index) =>
			@MakePrimary index

		# Submits the form to the backend if clicked
		@div.find('#upload_image').click () =>
			@div.find('#real-file-input').click()

		# Handles the character counter for the caption
		@div.find("#captionInput").keyup () =>
			caption = @div.find("#captionInput").val()
			if caption.length >= @MAX_CAPTION_LENGTH
				@div.find("#charactersLeft").css("color", "red")
			else
				@div.find("#charactersLeft").css("color", "black")

			@div.find("#charactersLeft").html(@MAX_CAPTION_LENGTH - caption.length)
			@_photos[@CurrentPreviewImage].SaveCaption caption

		# File upload based on library jquery.fileupload
		@div.find('#ImageAddForm').fileupload
			url: myBaseUrl + 'images/AddImage'
			dataType: 'json'
			acceptFileTypes: /(\.|\/)(jpeg|jpg|png)$/i
			singleFileUploads: true
			maxFileSize: @MAX_FILE_SIZE
			loadImageMaxFileSize: @MAX_FILE_SIZE
			disableImageResize: false,
			previewMaxWidth: 300
			previewMaxHeight: 300
			previewCrop: true

		# Check if there is already max number of pictures uploaded
		.on 'fileuploadadd', (e, data) =>
			if @_photos.size is 24
				A2Cribs.UIManager.Error "Sorry but Cribspot only allows for 24 pictures!"
				return false

		# Add the preview and display the error message if failed
		.on 'fileuploadprocessalways', (e, data) =>
			index = data.index
			file = data.files[index]
			# Failed validation
			if file.error?
				A2Cribs.UIManager.Error "Sorry - #{file.error}"
				return false
			# Create a new photo and add it to the list
			if file.preview?
				@CurrentUploadDeferred = $.Deferred()
				@div.find("#upload_image").button 'loading'
				# On upload deferred finish reset the button
				@CurrentUploadDeferred
				.always () =>
					@div.find("#upload_image").button 'reset'
				@AddPhoto new Photo @_photos.next_id, file.preview, @CurrentUploadDeferred

		# Callback for when the action is completed
		.on 'fileuploaddone', (e, data) =>
			if data.result?.error?
				A2Cribs.UIManager.Error data.result.error
				@CurrentUploadDeferred.reject()
			else
				@CurrentUploadDeferred.resolve data

		# Fail callback if image upload doesnt work
		.on 'fileuploadfail', (e, data) =>
			A2Cribs.UIManager.Error "Sorry something went wrong. Please retry photo upload."
			@CurrentUploadDeferred.reject()

	###
	Open
	###
	@Open: (image_array) ->
		@Load(image_array)
		@div.modal('show')
		@PhotoPickerDeferred = $.Deferred()
		return @PhotoPickerDeferred.promise()

	###
	Load
	Loads up the images to the photo manager
	given an image array
	###
	@Load: (image_array) ->
		# Reset the photo picker
		@Reset()

		# Look though the image_array
		if image_array?.length? # if this is a legit array
			for image in image_array
				@AddPhoto new Photo @_photos.next_id, image
			for key, photo of @_photos
				if photo.IsPrimary? and photo.IsPrimary()
					$(".image-row").trigger "set_primary", [photo.index]

		# Add Click Listener to the finish button
		@div.find("#finish_photo").unbind('click').click =>
			# If the picture is still uploading display working on it message
			if @CurrentUploadDeferred?.state() is 'pending'
				A2Cribs.UIManager.Success "Cribspot is still uploading your images. We'll be done in just a moment."
			# When complete close the window and resolve the photo diferred
			$.when(@CurrentUploadDeferred).then (resolved) =>
				@div.modal('hide')
				@PhotoPickerDeferred.resolve @GetPhotos()

	###
	Edit
	Takes an index into the A2Cribs.Image
	photo image array and displays it in the
	main photo section to be edited
	###
	@Edit: (index) ->
		@CurrentPreviewImage = index
		@div.find("#imageContent0").html @_photos[index].GetPreview()
		@div.find("#captionInput").val @_photos[index].GetCaption()
		@div.find("#charactersLeft").html @MAX_CAPTION_LENGTH - @_photos[index].GetCaption().length

	###
	Reset
	Resets the UI for a new image set or to
	load an existing set of images
	###
	@Reset: ->
		@ResetMainPhoto()
		@div.find(".image-row").empty()
		@CurrentPrimaryImage = null
		@_photos = 
			size: 0
			next_id: 0


	###
	Make Primary
	Unsets previous primary image and makes
	index primary
	###
	@MakePrimary: (index) ->
		if @CurrentPrimaryImage?
			@_photos[@CurrentPrimaryImage].UnsetPrimary()
		@_photos[@CurrentPrimaryImage = index].MakePrimary()

	###
	Delete
	Deletes the photo from both the UI and
	the backend if the photo has been saved
	###
	@Delete: (index) ->
		# Get the photo from the object
		photo = @_photos[index]

		# Remove the UI of the photo before the delete has finished
		@RemovePhoto index

		# When the delete is completed
		$.when(photo.DeleteDeferred)
		.done (response) =>
			# If there was an error deleting the photo
			if not response? or response.error?
				A2Cribs.UIManager.Error "Photo could not be deleted!"
				# Re-add the photo to the view
				@AddPhoto photo
			else
				A2Cribs.UIManager.Success "Photo was successfully deleted!"
		.fail (response) =>
			# Error deleting photo
			A2Cribs.UIManager.Error "Photo could not be deleted!"
			# Add the photo back to the view
			@AddPhoto photo

		# If the primary image was deleted
		if @CurrentPrimaryImage is index
			# Find a new primary image
			@DefaultPrimaryPhoto()
	###
	Add Photo
	Pushes photo onto the photo array and
	renders the preview for the photo box
	###
	@AddPhoto: (photo) ->
		@_photos[@_photos.next_id] = photo
		@_photos.size += 1
		@_photos.next_id += 1
		@div.find(".image-row").append photo.GetHtml()
		if not @CurrentPrimaryImage?
			@DefaultPrimaryPhoto()

	###
	Remove Photo
	Removes photo from the photo array and
	updates the UI to show there is no more
	photo
	###
	@RemovePhoto: (index) ->
		@_photos.size -= 1
		if @CurrentPreviewImage is index
			@div.find("#imageContent0").html "<div class = 'img-place-holder'></div>"
			@div.find("#captionInput").val ""
			@div.find("#charactersLeft").text @MAX_CAPTION_LENGTH

		delete @_photos[index]
		@div.find("#prev_#{index}").fadeOut()

	###
	Reset Main Photo
	Clears the UI for the main photo
	###
	@ResetMainPhoto: ->
		@div.find("#imageContent0").html "<div class = 'img-place-holder'></div>"
		@div.find("#captionInput").val ""
		@div.find("#charactersLeft").text @MAX_CAPTION_LENGTH
		@CurrentPreviewImage = null

	###
	Default Primary Photo
	Defaults the current primary image to the first
	Photo in _photos
	###
	@DefaultPrimaryPhoto: ->
		@CurrentPrimaryImage = null
		for key, photo of @_photos
			if photo.MakePrimary?
				@CurrentPrimaryImage = parseInt(key, 10)
				photo.MakePrimary()
				break

	###
	Get Photos
	Returns an array of all the images that are in the
	photo picker
	###
	@GetPhotos: ->
		results = []
		for key, photo of @_photos
			if photo.GetObject?
				results.push photo.GetObject()

		return results

	###
	Document ready
	Waits for the document to be loaded.
	When loaded creates all the listeners
	needed to connect the UI
	###
	$(document).ready =>
		if $("#picture-modal").length
			@SetupUI $("#picture-modal")