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
	public function AddImage($listing_id, $files, $user_id)
	{
		$relative_path = 'img/sublets/' . $listing_id;
		$folder = WWW_ROOT . $relative_path;
		$errors = array();

		// handle primary image
		if ($files[0] != null)
		{
			if ($files[0]['size'] > $this->MAX_FILE_SIZE)
			{
				array_push($errors, array("primary" => "FILE_TOO_LARGE"));
			}
			else
			{
				$fileType = substr($files[0]['name'], strrpos($files[0]['name'], '.') + 1);
				$name = "primary." . $fileType;
				$filePath = $relative_path . "/" . $name;

				// create the folder if it does not exist
				if(!is_dir($folder)) {
					mkdir($folder);
				}

				if ($this->AddImageEntry($listing_id, $user_id, $filePath))
				{
					// move file to folder named by its listing_id
					if (!move_uploaded_file($files[0]["tmp_name"], $filePath))
					{
						//CakeLog::write('debug', 'failed to move file to ' . $filePath);
					}
					else
					{
						//CakeLog::write('debug', 'successfully moved file to ' . $filePath);
					}	
				}
				else
					array_push($errors, array("secondary_" . $i => "VALIDATION_FAILED"));
			}
		}

		// handle secondary images
		for ($i = 0; $i < count($files[1]); $i++)
		{
			if ($files[1][$i] == null)
				continue;
			if ($files[1][$i]['size'] > $this->MAX_FILE_SIZE)
			{
				array_push($errors, array("secondary_" . $i => "FILE_TOO_LARGE"));
				continue;
			}
			$fileType = substr($files[1][$i]['name'], strrpos($files[1][$i]['name'], '.') + 1);
			$name = "secondary_" . $i . "." . $fileType;

			$filePath = $relative_path . "/" . $name;

			// create the folder if it does not exist
			if(!is_dir($folder)) {
				mkdir($folder);
			}

			if ($this->AddImageEntry($listing_id, $user_id, $filePath))
			{
					// move file to folder named by its listing_id
				if (!move_uploaded_file($files[1][$i]["tmp_name"], $filePath))
				{
					//CakeLog::write('debug', 'failed to move file to ' . $filePath);
				}
				else
				{
					//CakeLog::write('debug', 'successfully moved file to ' . $filePath);
				}
			}
			else
				array_push($errors, array("secondary_" . $i => "VALIDATION_FAILED"));
		}
		return $errors;
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

	public function DeleteImage($listing_id, $path)
	{
		$relative_path = substr($path, 1);
		$path = WWW_ROOT . substr($path, 1);	

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
				return "true";
			else
				return "false1";
		}

		return "false2";
	}
}
?>
