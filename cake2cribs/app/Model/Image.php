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
		'path'    => array(
			'rule' => array('extension', array('jpeg', 'png', 'jpg')),     // path to image, starting /app/webroot
			'message' => 'Please supply a valid image, either jpeg, jpg, or png'
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

		// handle primary image
		if ($file != null)
		{
			if ($file['size'][0] > $this->MAX_FILE_SIZE)
			{
				array_push($errors, array("primary" => "FILE_TOO_LARGE"));
			}
			else
			{
				$fileType = substr($file['name'][0], strrpos($file['name'][0], '.') + 1);
				CakeLog::write('imageDebug', $fileType);
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
						//CakeLog::write('debug', 'failed to move file to ' . $filePath);
					}
					else
					{
						//CakeLog::write('debug', 'successfully moved file to ' . $filePath);
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
			'listing_id' => $listing_id, 
			'user_id' => $user_id,
			'path' => $filePath
		);

		$conditions = array(
			'Image.listing_id' => $listing_id,
			'Image.user_id' => $user_id,
			'Image.path' => $filePath);

		if (!$this->hasAny($conditions))
		{
			$this->create();

			if (!$this->save($newImage))
			{
				CakeLog::write("validationErrors", $listing_id . " | " . $user_id . " | " . $filePath);
				return false;
			}

			return true;
		}

		return false;
	}

	public function getImagesForListingId($listing_id)
	{
		$files = array();
		$folder = WWW_ROOT . 'img/sublets/' . intval($listing_id);

		if ($handle = opendir($folder)) 
		{
		    while (false !== ($entry = readdir($handle))) {
		    	if (strrpos($entry, "png") != false || strrpos($entry, "jpg") != false || strrpos($entry, "jpeg") != false)
		        	array_push($files, $entry);
		    }
		}
		
		return $files;
	}

	public function DeleteImage($user_id, $listing_id, $path)
	{

		//TODO: TAKE THIS OUT
		if ($user_id == null)
			$user_id = 0;

		//make sure the image being deleted is owned by the current user
		$conditions = array(
			'Image.user_id' => $user_id,
			'Image.listing_id' => $listing_id);

		if (!$this->hasAny($conditions)){
			// User can't delete image from a listing that is not their own.
			return "IMAGE_NOT_OWNED_BY_USER " . $user_id . " " . $listing_id;
		}

		$relative_path = $path;
		$path = WWW_ROOT . $path;

		$conditions = array(
			'Image.listing_id' => $listing_id,
			'Image.path' => $relative_path);

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

	
}
?>
