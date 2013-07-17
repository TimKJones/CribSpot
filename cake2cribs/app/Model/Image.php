<?php 

class Image extends AppModel {
	public $name = 'Image';
	public $primaryKey = 'image_id';
	public $actsAs = array('Containable');
	public $belongsTo = array(
		'Listing' => array(
            'className'    => 'Listing',
            'foreignKey'   => 'listing_id'
        )
	);
	public $MAX_FILE_SIZE = 5242880; // in bytes (5 MB)

	public $validate = array(
		'image_id' => 'numeric',
		'user_id' => 'numeric',
		'listing_id' => 'numeric', // listing to which this image belongs
		'image_path'    => array(
			'rule' => array('extension', array('jpeg', 'png', 'jpg')),     // path to image, starting /app/webroot
			'message' => 'Please supply a valid image, either jpeg, jpg, or png'
		),
		'is_primary' => 'boolean',
		'caption' => array(
			'maxLength' => array(
				'rule' => array('maxLength', 25)
			)
		)
	);

	public function beforeValidate($options = array()) {
		if (empty($this->data[$this->alias]['sublet_id'])) {
			unset($this->validate['sublet_id']);
		}

		return true;
	}

	/*
	VARIABLES: 
	$file = $image data
	$row_id = row  of listing in user's current slickgrid
	$listing_id = listing_id of of this listing, if already saved.
	Moves file to /img/listings/random_id
	Add new record to images table
	Returns the new image_id on success; error message on failure.
	*/
	public function SaveImage($file, $user_id, $listing_id = null, $currentPath=null)
	{
		if (!array_key_exists('name', $file) || !array_key_exists(0, $file['name']))
			return array('error' => 'error2 saving image');

		if ($currentPath == null)
			$currentPath = 'img/listings/';

		$random = uniqid();
		$newPath = $currentPath . $random;
		$fileType = $this->_getFileType($file);
		if (!is_file(WWW_ROOT . $newPath . '.' . $fileType)){
			/* File doesn't exist yet. This is the path where the image will be saved. */
			$newPath = $newPath . '.' . $fileType;
			$response = $this->MoveFileToFolder($file, WWW_ROOT . $newPath, $user_id);
			if (array_key_exists('error', $response))
				return $response;

			$image_id = $this->AddImageEntry($newPath, $user_id, $listing_id);
			return $image_id;
		}

		/* File name already exists. Create new folder if $newPath doesn't already exist */
		if (!is_dir($newPath)){
			if (!$this->_createFolder($newPath)){
				$error = null;
				$error['new_path'] = $newPath;
				$this->LogError($user_id, 14, $error);
				return array('error' => 'There was an error saving your image. ' . 
					'Contact help@cribspot.com if the error persists. Reference error code 14');
			}
		}

		$newPath = $newPath . '/';
		return $this->SaveImage($file, $user_id, $listing_id, $newPath);
	}

	/*
	Moves file to the path specified.
	Returns true on success; false on failure
	REQUIRES: $file contains the array keys ['name'][0] to extract the file name.
	*/
	public function MoveFileToFolder($file, $newPath, $user_id)
	{
		if (!$this->_isValidFileSize($file)) {
			$error = null;
			$error['file'] = $file;
			$this->LogError($user_id, 21, $error);
			return array('error' => 'File is too large.' . 
				'Contact help@cribspot.com if you believe this is an error. Reference error code 21.');
		}

		if (!$this->_isValidFileType($file, $user_id)){
			$error = null;
			$error['file'] = $file;
			$this->LogError($user_id, 22, $error);
			return array('error' => 'Invalid file type. Image must be jpeg, jpg, or png.' . 
				'Contact help@cribspot.com if you believe this is an error. Reference error code 22.');
		}

		if (!$this->_createFolder($this->_getDeepestDirectoryFromPath($newPath))){
			$error = null;
			$error['file'] = $file;
			$error['path'] = $newPath;
			$this->LogError($user_id, 18, $error);
			return array(
			'There was an error saving your image. Contact help@cribspot.com if the error persists. Reference error code 18');
		}

		if (!array_key_exists('tmp_name', $file) || !array_key_exists(0, $file['tmp_name'])){
			$error = null;
			$error['file'] = $file;
			$this->LogError($user_id, 19, $error);
			return array(
			'There was an error saving your image. Contact help@cribspot.com if the error persists. Reference error code 19');
		}

		if (!move_uploaded_file($file['tmp_name'][0], $newPath)){
			$error = null;
			$error['path'] = $newPath;
			$error['file'] = $file;
			$this->LogError($user_id, 20, $error);
			return array(
			'There was an error saving your image. Contact help@cribspot.com if the error persists. Reference error code 20');
		}

		return array('success' => 'file moved successfully'); 
	}

	/*
	Add a record to the images table for the given file path.
	*/
	private function AddImageEntry($filePath, $user_id, $listing_id = null)
	{
		$newImage = array(
			'image_path' => $filePath,
			'user_id' => $user_id,
			'is_primary' => 0
		);

		if ($listing_id != null)
			$newImage['listing_id'] = $listing_id;

		if ($this->save($newImage))
			return array('image_id' => $this->id);
		else{
			$error = null;
			$error['image'] = $newImage;
			$error['validation'] = $this->validationErrors;
			$this->LogError($user_id, 15, $error);
			return array('error' => 
				'Failed to save image. Contact help@cribspot.com if the error persists. Reference error code 15.');
		}
	}

	/*
	Deletes the images with paths in records to be deleted.
	Deletes the image records with ids in $image_ids.
	*/
	public function DeleteExpiredImages($image_ids)
	{
		/* Get all image paths to delete */
		$imagePaths = $this->find('all', array(
			'fields' => array('Image.image_path'), 
			'conditions' => array('Image.image_id' => $image_ids)
		));

		/* Delete all images from img/listings/incomplete/user_id/row_id */
		for ($i = 0; $i < count($imagePaths); $i++) {
			$this->DeleteImageFile($imagePaths[$i]['Image']['image_path']);
		}

		$this->DeleteImageRecords($image_ids);
	}

	/*
	Called after a listing is saved to update the listings images that were saved before listing_id was known.
	$listing_id = id of listing that was just saved.
	$images  = image objects to be re-saved.
	Returns error message on failure.
	*/
	public function UpdateAfterListingSave($listing_id, $images, $user_id=null)
	{
		$errors = false;
		for ($i = 0; $i < count($images); $i++){
			if (!array_key_exists($i, $images) ||
				!array_key_exists('Image', $images[$i]) ||
				!array_key_exists('image_id', $images[$i]['Image'])){
				$error = null;
				$error['images'] = $images;
				$this->LogError($user_id, 16, $error);
				return array('error' => 
					'Failed to save listing. Contact help@cribspot.com if the error persists. Reference error code 16.');
			}

			$images[$i]['Image']['listing_id'] = $listing_id;
		}

		if (!$this->save($images)){
			$error = null;
			$error['image'] = $images[$i];
			$error['validation'] = $this->validationErrors;
			$this->LogError($user_id, 17, $error);
			return array('error' => 
					'Failed to save listing. Contact help@cribspot.com if the error persists. Reference error code 17');
		}

		return array('success' => '');
	}	

	/*
	Moves file from $currentPath to $newPath
	Returns true on success; false on failure.
	*/
	private function _moveImageAfterListingSave($currentPath, $newPath)
	{
		if (!$this->_createFolder($this->_getDeepestDirectoryFromPath($newPath))) {
			CakeLog::write("movingImage", "failed to move " . $currentPath . " to " . $newPath);
			return false;
		}

		if (!rename($currentPath, $newPath)){
			CakeLog::write("movingImage", "failed to rename " . $currentPath . " to " . $newPath);
			return false;
		}

		return true;
	}

	/*
	Sometimes files aren't deleted after moving them to /img/listings/listing_id.
	Delete all files in $directory
	*/
	private function _deleteDirectory($dir) 
	{
	    if (!file_exists($dir)) 
	    	return true;
	    if (!is_dir($dir)) 
	    	return unlink($dir);
	    foreach (scandir($dir) as $item) {
	        if ($item == '.' || $item == '..') 
	        	continue;
	        if (!$this->_deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) 
	        	return false;
	    }
    	return rmdir($dir);
	}

	/*
	Return the new path for image with $listing_id and old path $old_path
	*/
	public function GetNewRelativePathAfterListingSave($old_path, $listing_id)
	{
		$fileName = $this->_getFileNameFromPath($old_path);
		$newPath = 'img/listings/' . $listing_id . '/' . $fileName;
		return $newPath;
	}


	/* 
	------- NOT TO BE USED ANYMORE. THIS WAS USED WITH SUBLETS.     --------
	------- WHEN NEW VERSION IS FULLY TESTED, THIS WILL BE REMOVED. --------
	Create new row in images table for this image.
	Move image to new location in img/sublets/[sublet_id]_img#
	returns true on success, false on failure
	*/
	public function AddImage($file, $user_id)
	{
		CakeLog::write("fileDebug", "file data: " . print_r($file, true));
		$relative_path = 'img/sublets';
		$folder = WWW_ROOT . $relative_path;
		$errors = array();
		$filePath = null;

		if ($file != null)
		{
			if ($file['size'][0] > $this->MAX_FILE_SIZE)
			{
				array_push($errors, array("primary" => "FILE_TOO_LARGE"));
			}
			else
			{
				$fileType = substr($file['name'][0], strrpos($file['name'][0], '.') + 1);
				if ($fileType != "jpg" && $fileType != "jpeg" && $fileType != "png")
					array_push($errors, "INVALID_FILE_TYPE");

				$name = uniqid() . "." . $fileType;
				$filePath = $relative_path . "/" . $name;

				// create the folder if it does not exist
				CakeLog::write("imageDebug", "folder: " . $folder);
				if(!is_dir($folder)) {
					$temp = mkdir($folder);
					CakeLog::write("imageDebug", "folder return value: " . $temp == false);
				}
				else
					CakeLog::write("imageDebug", "directory exists");

				$image_id = $this->AddImageEntry($user_id, $filePath);

				if ($image_id != null)
				{
					// move file to folder named by its listing_id
					if (!move_uploaded_file($file["tmp_name"][0], $filePath))
					{
						CakeLog::write('imageDebug', 'failed to move file to ' . $filePath);
					}
					else
					{
						CakeLog::write('imageDebug', 'successfully moved file to ' . $filePath);
					}	
				}
				else
					array_push($errors, "VALIDATION_FAILED");
			}
		}

		CakeLog::write("fileDebug", "errors: " . print_r($errors, true));
		$response = array('errors' => $errors, 'id' => $image_id);
		CakeLog::write('imageDebug', "data: " . print_r($response, true));
		return $response;
	}


	/*
	This is the old method for adding to the images table.
	*/
	private function AddImageEntry_old($user_id, $filePath)
	{
		$newImage = array(
			'sublet_id' => null, 
			'user_id' => $user_id,
			'image_path' => $filePath,
			'is_primary' => 0
		);

		//CakeLog::write("addImageSql", print_r($newImage, true));

		$conditions = array(
			'Image.sublet_id' => null,
			'Image.user_id' => $user_id,
			'Image.image_path' => $filePath);
		$test = $this->hasAny($conditions);

		if (!$this->hasAny($conditions))
		{
			$this->create();
			if (!$this->save($newImage))
			{
				//CakeLog::write("addImageSql", "FAILED TO SAVE IMAGE");
				return null;
			}

			//CakeLog::write("addImageSql", "SUCCESSFULLY ADDED IMAGE ENTRY");
			return $this->id;
		}

		//CakeLog::write("addImageSql", "IMAGE RECORD ALREADY EXISTS");
		return null;
	}

	public function UpdateImageEntry($user_id, $sublet_id, $image)
	{
		CakeLog::write('imageDebug', "Image to be updated: " . print_r($image, true));
		$owner = $this->find('first', array(
			'fields' => array('user_id'), 
			'conditions' => array('Image.image_id' => $image['image_id'])
			)
		);
		if ($owner['Image']['user_id'] == $user_id)
		{
			foreach ($image as $key => $value)
			{
				$this->saveField($key, $value);
			}
			$this->saveField('sublet_id', $sublet_id);
		}

	}

	public function getImagesForListingId($listing_id)
	{
		$files = array();
		$captions = array();

		$primary_image_query = $this->find('all', array(
			'conditions' => array('Image.sublet_id' => $listing_id),
			'fields' => 	array('image_path', 'is_primary', 'caption')));
		$primary_image_index = 0;

		CakeLog::write("loadingImages", print_r($primary_image_query, true));
		for ($i = 0; $i < count($primary_image_query); $i++)
		{
			array_push($files, $primary_image_query[$i]['Image']['image_path']);
			array_push($captions, $primary_image_query[$i]['Image']['caption']);
			if ($primary_image_query[$i]['Image']['is_primary'])
				$primary_image_index = $i;
		}

		/*$folder = WWW_ROOT . 'img/sublets/' . intval($listing_id);

		if ($handle = opendir($folder)) 
		{
		    while (false !== ($entry = readdir($handle))) {
		    	if (strrpos($entry, "png") != false || strrpos($entry, "jpg") != false || strrpos($entry, "jpeg") != false)
		        	array_push($files, $entry);
		    }
		}*/
		$returnVal = array();
		array_push($returnVal, $primary_image_index, $files, $captions);
		return $returnVal;
	}

	/*
	Deletes the file with the specified path
	*/
	public function DeleteImageFile($image_path)
	{
		$image_path = WWW_ROOT . $image_path;
		return unlink($image_path);
	}

	/*
	Deletes all image records with ids contained in $image_ids
	*/
	public function DeleteImageRecords($image_ids)
	{
		$this->deleteAll(
			array('Image.image_id' => $image_ids), false
		);
	}

	public function DeleteImage($user_id, $listing_id, $path)
	{

		//TODO: TAKE THIS OUT
		if ($user_id == null)
			$user_id = 0;

		//make sure the image being deleted is owned by the current user
		$conditions = array(
			'Image.user_id' => $user_id,
			'Image.sublet_id' => $listing_id);

		if (!$this->hasAny($conditions)){
			// User can't delete image from a listing that is not their own.
			return "IMAGE_NOT_OWNED_BY_USER " . $user_id . " " . $listing_id;
		}

		$relative_path = $path;
		$path = WWW_ROOT . $path;

		$conditions = array(
			'Image.sublet_id' => $listing_id,
			'Image.image_path' => $relative_path);

		if ($this->hasAny($conditions))
		{
			// delete the db record
			$image_id = $this->find('all', array(
				'conditions' => $conditions,
				'fields' => 'Image.image_id'));
			$this->delete($image_id[0]['Image']['image_id']);

			// delete the file
			if (unlink($path))
			{
				return "DELETE_SUCCESSFUL";
			}
			else
				return "DELETE_FAILED";
		}

		return "false2 - " . $relative_path;
	}

	// set the image with image_path = $path as the primary image for the sublet with sublet_id=$listing_id
	function MakePrimary($listing_id, $path)
	{
		CakeLog::write("makePrimary", "listing_id: " . $listing_id . " | path: " . $path);

		// set is_primary to false for previous primary.
		$this->UnsetPrimaryImage($listing_id);

		// set the image with image_path = $path as the primary image
		$image_id_query = $this->find('first', array(
			'conditions' => array('Image.sublet_id' => $listing_id,
								  'Image.image_path'=> $path),
			'fields' => 	array('image_id')));

		if ($image_id_query && $image_id_query['Image'] && $image_id_query['Image']['image_id'])
		{
			$image_id = $image_id_query['Image']['image_id']; 
			$this->id = $image_id;
			CakeLog::write("makePrimary", "image_id: " . $image_id);

			if (!$this->saveField('is_primary', true))
				CakeLog::write("makePrimary", "FAILED: " . $listing_id . " | " . $path);
		}
		else
			CakeLog::write("makePrimary", "FAILED2: " . print_r($image_id_query, true));

	}

	// unset is_primary for any image with sublet_id = $listing_id
	function UnsetPrimaryImage($listing_id)
	{
		$unset_primary_query = $this->find('first', array(
			'conditions' => array('Image.is_primary' => true,
								  'Image.sublet_id' => $listing_id),
			'fields' => 	array('image_id')));

		if ($unset_primary_query && $unset_primary_query['Image'] && $unset_primary_query['Image']['image_id'])
		{
			$image_id = $unset_primary_query['Image']['image_id'];
			$this->id = $image_id;
			if (!$this->saveField('is_primary', false))
				CakeLog::write("makePrimary", "FAILED: " . $listing_id . " | " . $path);
		}
	}

	function SubmitCaption($caption, $user_id, $path)
	{
		$image_id_query = $this->find('first', array(
			'conditions' => array('Image.image_path' => $path,
								  'Image.user_id' => $user_id),
			'fields' => 	array('image_id')));
		CakeLog::write("addCaption", print_r($image_id_query, true));
		$image_id = $image_id_query['Image']['image_id'];
		$this->id = $image_id;
		if (!$this->saveField('caption', $caption))
		{
			CakeLog::write("addCaption", "FAILED: " . $caption . " | " . $path);
			return "VALIDATION_FAILED";
		}

		return "SUCCESS";
	}

	/*
	Returns the file name given the full path to the file
	*/
	private function _getFileNameFromPath($image_path)
	{
		return substr($image_path, strrpos($image_path, '/') + 1);
	}

	/*
	Returns the deepest directory from a given path.
	*/
	private function _getDeepestDirectoryFromPath($path)
	{
		return substr($path, 0, strrpos($path, '/'));
	}

	/*
	Creates new folder with given path.
	Returns true on success; false on failure
	*/
	private function _createFolder($path)
	{
		if(!is_dir($path)){
			if (!mkdir($path, 0777, true))
				return false;
		}

		return true;
	}

	/*
	Returns true if file is of valid file type (jpg, jpeg, png); false otherwise
	*/
	private function _isValidFileType($file, $user_id)
	{
		if (!array_key_exists('name', $file) || !array_key_exists(0, $file['name']))
			return false;

		$fileType = $this->_getFileType($file);
		if ($fileType != "jpg" && $fileType != "jpeg" && $fileType != "png")
			return false;
		
		return true;		
	}

	/*
	Returns true if file is smaller than the maximum file size; false otherwise
	*/
	private function _isValidFileSize($file)
	{
		if (array_key_exists('size', $file) && array_key_exists(0, $file['size']))
			return ($file['size'][0] <= $this->MAX_FILE_SIZE);

		return false;
	}

	/*
	Returns the file type from a file path
	*/
	private function _getFileType($file)
	{
		return substr($file['name'][0], strrpos($file['name'][0], '.') + 1);
	}
}
