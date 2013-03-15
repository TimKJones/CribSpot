<?php 

class Image extends AppModel {
	public $name = 'Image';
	public $primaryKey = 'image_id';
	//public $belongsTo = array('Listing');
	public $MAX_FILE_SIZE = 5242880; // in bytes (5 MB)

	public $validate = array(
		'image_id' => 'alphaNumeric',
		'sublet_id' => 'alphaNumeric', // listing to which this image belongs
		'user_id' => 'alphaNumeric',    // user that uploaded this photo
		'image_path'    => array(
			'rule' => array('extension', array('jpeg', 'png', 'jpg')),     // path to image, starting /app/webroot
			'message' => 'Please supply a valid image, either jpeg, jpg, or png'
		),
		'is_primary' => 'boolean',
		'caption' => array(
			'alphaNumeric' => array(
				'rule' => 'alphaNumeric'),
			'maxLength' => array(
				'rule' => array('maxLength', 25)
			)
		)
	);	

	/* 
	Create new row in images table for this image.
	Move image to new location in img/sublets/[sublet_id]_img#
	returns true on success, false on failure
	*/
	public function AddImage($listing_id, $file, $user_id)
	{
		CakeLog::write("fileDebug", "file data: " . print_r($file, true));
		$relative_path = 'img/sublets/' . $listing_id;
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

				if ($this->AddImageEntry($listing_id, $user_id, $filePath))
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
		$response = array();
		array_push($response, $errors, $filePath);
		return $response;
	}

	private function AddImageEntry($listing_id, $user_id, $filePath)
	{
		$newImage = array(
			'sublet_id' => $listing_id, 
			'user_id' => $user_id,
			'image_path' => $filePath,
			'is_primary' => 0
		);

		//CakeLog::write("addImageSql", print_r($newImage, true));

		$conditions = array(
			'Image.sublet_id' => $listing_id,
			'Image.user_id' => $user_id,
			'Image.image_path' => $filePath);
		$test = $this->hasAny($conditions);

		if (!$this->hasAny($conditions))
		{
			$this->create();
			if (!$this->save($newImage))
			{
				//CakeLog::write("addImageSql", "FAILED TO SAVE IMAGE");
				return false;
			}

			//CakeLog::write("addImageSql", "SUCCESSFULLY ADDED IMAGE ENTRY");
			return true;
		}

		//CakeLog::write("addImageSql", "IMAGE RECORD ALREADY EXISTS");
		return false;
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
			CakeLog::write("addCaption", "FAILED: " . $caption . " | " . $path);
	}
}
