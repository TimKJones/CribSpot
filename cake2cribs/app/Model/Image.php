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
			'rule' => array('extension', array('jpeg', 'png', 'jpg', 'JPG', 'JPEG', 'PNG')),     // path to image, starting /app/webroot
			'message' => 'Please supply a valid image, either jpeg, jpg, or png'
		),
		'is_primary' => 'boolean',
		'caption' => array(
			'maxLength' => array(
				'rule' => array('maxLength', 25)
			)
		)
	);

	/*
	array of image prefix to (width, height)
	*/
	private $file_prefixes = array(
		'sml_'=>array(
			'width' => 98,
			'height' => null
		), 
		'med_'=>array(
			'width' => 260,
			'height' => null
		), 
		'lrg_'=>array(
			'width' => null,
			'height' => null
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
	$listing_id = listing_id of of this listing, if already saved.
	Moves file to /img/listings/random_id
	Add new record to images table
	Returns the new image_id on success; error message on failure.
	*/
	public function SaveImage($file, $user_id, $listing_id = null, $currentPath=null)
	{
		if (!array_key_exists('name', $file) || !array_key_exists(0, $file['name'])){
			$error = null;
			$error['file'] = $file;
			$this->LogError($user_id, 40, $error);
			return array('error' => 'Looks like we had some problems saving your image! We want to help! If this issue continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 40.');
		}

		if ($currentPath == null)
			$currentPath = 'img/listings/';

		$random = uniqid();
		$newPath = $currentPath . $random;
		/* To test for this file's existence, we need only check one of its sizes */
		$testPath = $currentPath . 'lrg_' . $random;

		$fileType = $this->_getFileType($file);
		if (!is_file(WWW_ROOT . $testPath . '.' . $fileType)){
			/* File doesn't exist yet. This is the path where the image will be saved. */
			foreach ($this->file_prefixes as $prefix=> $dimensions){
				$prependedPath = $currentPath . $prefix . $random . '.' . $fileType;
				$response = $this->MoveFileToFolder($file, $prefix, WWW_ROOT . $prependedPath, $user_id);
				if (array_key_exists('error', $response))
					return $response;
			}

			return $this->AddImageEntry($newPath.'.'.$fileType, $user_id, $listing_id);
		}

		/* File name already exists. Create new folder if $newPath doesn't already exist */
		if (!is_dir($newPath)){
			if (!$this->_createFolder($newPath)){
				$error = null;
				$error['new_path'] = $newPath;
				$this->LogError($user_id, 14, $error);
				return array('error' => 'Looks like we had some problems saving your image! We want to help! If this issue continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 14.');
			}
		}

		$newPath = $newPath . '/';
		return $this->SaveImage($file, $user_id, $listing_id, $newPath);
	}

	/*
	Only to be called when importing listings to prepare for a university launch
	Moves file with path $image_path to $new_directory
	Then adds record to images table.
	*/
	public function SaveImageFromImport($image_path, $user_id, $listing_id, $is_primary, $new_directory = 'img/listings/')
	{
	//CakeLog::write('arguments', $image_path . ' . ' . $user_id . ' . ' . $is_primary . ' . ' . $new_directory);
		$fileType = substr($image_path, strrpos($image_path, '.') + 1);
		$newPath = $new_directory . uniqid() . '.' . $fileType;
	//CakeLog::write('new_path', $newPath);
		/* Move file to $new_path */

		$image = imagecreatefromjpeg($image_path);
        imagejpeg($image, $newPath, 45);

		/*if (!copy($image_path, WWW_ROOT.$newPath)){
			CakeLog::write('failed_to_move_image', $image_path . '; user_id = ' . $user_id . '; listing_id: ' . $listing_id);
			return array('error' => '');
		}*/

		/* Create image entry for this image */
		$this->create(); // call this to reset $this->id from previous save
		$response = $this->AddImageEntry($newPath, $user_id, $listing_id, $is_primary);
	CakeLog::write('AddImageEntry_response', print_r($response, true));
		if (array_key_exists('error', $response))
		{
			CakeLog::write('failed_to_save_image_entry', $newPath . '; ' . $user_id . '; ' . $listing_id);
			return array('error' => '');
		}

		return $response;
	}	

	/*
	Moves file to the path specified.
	Returns true on success; false on failure
	REQUIRES: $file contains the array keys ['name'][0] to extract the file name.
	*/
	public function MoveFileToFolder($file, $prefix, $newPath, $user_id)
	{
		if (!$this->_isValidFileSize($file)) {
			$error = null;
			$error['file'] = $file;
			$this->LogError($user_id, 21, $error);
			return array('error' => "Your file is a little too big.  We don't accept images over 5 MB. If you're having issues, " .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 21.');
		}

		if (!$this->_isValidFileType($file, $user_id)){
			$error = null;
			$error['file'] = $file;
			$this->LogError($user_id, 22, $error);
			return array('error' => "Sorry, we only accept jpeg, jpg, or png images.  If you're having issues, " .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 22.');
		}

		if (!$this->_createFolder($this->_getDeepestDirectoryFromPath($newPath))){
			$error = null;
			$error['file'] = $file;
			$error['path'] = $newPath;
			$this->LogError($user_id, 18, $error);
			return array('error' => "Looks like we had some problems saving your image! We want to help! If the issue continues, " .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 18.');
		}

		if (!array_key_exists('tmp_name', $file) || !array_key_exists(0, $file['tmp_name'])){
			$error = null;
			$error['file'] = $file;
			$this->LogError($user_id, 19, $error);
			return array('error' => 'Looks like we had some problems saving your image! We want to help! If the issue continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 19.');
		}

		/* Process image differently depending on its type */
		App::import('WideImage', 'WideImage');
		$response = array();
		$error = array();

		if ($prefix === 'sml_' || $prefix === 'med_'){
			$image = WideImage::load($file['tmp_name'][0]);
			if ($image === null){
				$error['path'] = $newPath;
				$error['file'] = $file;
				$this->LogError($user_id, 63, $error);
				return array('error' => 'Looks like we had some problems saving your image! We want to help! If the issue continues, ' .
					'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
						'at help@cribspot.com. Reference error code 63.');
			}

			$image->resize($this->file_prefixes[$prefix]['width'], $this->file_prefixes[$prefix]['height'])->saveToFile($newPath);
			
		}
		else {
			if (!move_uploaded_file($file['tmp_name'][0], $newPath)){
				$error = null;
				$error['path'] = $newPath;
				$error['file'] = $file;
				$this->LogError($user_id, 62, $error);
				return array('error' => 'Looks like we had some problems saving your image... but we want to help! If the issue continues, ' .
					'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
						'at help@cribspot.com. Reference error code 62.');
			}
		} 

		return array('success' => 'file moved successfully'); 
	}

	/*
	Add a record to the images table for the given file path.
	*/
	private function AddImageEntry($filePath, $user_id, $listing_id = null, $is_primary=0)
	{
		$newImage = array(
			'image_path' => $filePath,
			'user_id' => $user_id,
			'is_primary' => $is_primary
		);

		if ($listing_id !== null)
			$newImage['listing_id'] = $listing_id;

		if ($this->save($newImage))
			return array('image_id' => $this->id, 'image_path' => $filePath);
		else{
			$error = null;
			$error['image'] = $newImage;
			$error['validation'] = $this->validationErrors;
			$this->LogError($user_id, 15, $error);
			return array('error' => 'Looks like we had some problems saving your image! We want to help! If the issue continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 15.');
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
				return array('error' => 'Looks like we had some problems saving your listing! We want to help! If the issue continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 16.');
			}

			$images[$i]['Image']['listing_id'] = $listing_id;
		}

		if (!$this->save($images)){
			$error = null;
			$error['image'] = $images[$i];
			$error['validation'] = $this->validationErrors;
			$this->LogError($user_id, 17, $error);
			return array('error' => 'Looks like we had some problems saving your listing! We want to help! If the issue continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 17.');
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

	public function UpdateImageEntry($user_id, $sublet_id, $image)
	{
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
		$image_id = $image_id_query['Image']['image_id'];
		$this->id = $image_id;
		if (!$this->saveField('caption', $caption))
		{
			CakeLog::write("addCaption", "FAILED: " . $caption . " | " . $path);
			return "VALIDATION_FAILED";
		}

		return "SUCCESS";
	}

	public function GetPrimaryImagesByListingIds($listing_ids)
	{
		/* Data is corrupt somehow....must parse into new array */
		$uncorruptedListingIds = array();
		foreach ($listing_ids as $id)
			array_push($uncorruptedListingIds, $id);

		$images = $this->find('all', array(
			'conditions' => array(
				'Image.listing_id' => $uncorruptedListingIds,
				'Image.is_primary' => 1
			),
			'contains' => array(),
			'fields' => array('Image.image_id', 'Image.image_path', 'Image.listing_id', 'Image.is_primary')
		));

		return $images;
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
		$fileType = strtolower($fileType);
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
