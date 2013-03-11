<?php

class ImagesController extends AppController {
	public $helpers = array('Html', 'Js');
	public $uses = array('Image');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
		$this->Auth->allow('add');
		$this->Auth->allow('add2');
		$this->Auth->allow('add3');
		$this->Auth->allow('edit');
		$this->Auth->allow('LoadImages');
		$this->Auth->allow('DeleteImage');
	}
	function add($data = null)
	{
		$this->set('errors', null);
		$from_add_page = false;
		if (!$data)
		{
			$data = $this->data;
			$from_add_page = true;
		}

		$listing_id = 5; //TODO: update this
		$primary = null;
		$secondary = array();
		array_push($secondary, null);
		array_push($secondary, null);
		array_push($secondary, null);

		if ($this->data)
		{
			if (array_key_exists("primary", $this->data["Image"]) and $this->data["Image"]["primary"]["error"] === UPLOAD_ERR_OK)
				$primary = $this->data["Image"]["primary"];
			if (array_key_exists("secondary_1", $this->data["Image"]) and $this->data["Image"]["secondary_1"]["error"] === UPLOAD_ERR_OK)
				$secondary[0] = $this->data["Image"]["secondary_1"];
			if (array_key_exists("secondary_2", $this->data["Image"]) and $this->data["Image"]["secondary_2"]["error"] === UPLOAD_ERR_OK)
				$secondary[1] = $this->data["Image"]["secondary_2"];
			if (array_key_exists("secondary_3", $this->data["Image"]) and $this->data["Image"]["secondary_3"]["error"] === UPLOAD_ERR_OK)
				$secondary[2] = $this->data["Image"]["secondary_3"];
		}
		else
			return;

		// fill in gaps if this was originated from the 'add' page
		if ($from_add_page)
		{
			for ($i = 0; $i < count($secondary) - 1; $i++)
			{
				$left = $i;
				$right = $i+1;
				if ($secondary[$left] != null)
					continue;
				while($right < count($secondary) && $secondary[$left] == null && $secondary[$right] == null)
					$right ++;

				if ($right < count($secondary))
				{
					$secondary[$left] = $secondary[$right];
					$secondary[$right] = null;
				}
			}
		}

		$files = array();
		array_push($files, $primary);
		array_push($files, $secondary);

    	$errors = $this->Image->AddImage($listing_id, $files, $this->Session->read('user'));
    	$response = json_encode($errors);
    	$this->set('errors', $response);
    	return $response;
	}

	function add2($data = null)
	{
		$this->set('errors', null);
		$from_add_page = false;
		if (!$data)
		{
			$data = $this->data;
			$from_add_page = true;
		}

		$listing_id = 5; //TODO: update this
		$primary = null;
		$secondary = array();
		array_push($secondary, null);
		array_push($secondary, null);
		array_push($secondary, null);

		if ($this->data)
		{
			if (array_key_exists("primary", $this->data["Image"]) and $this->data["Image"]["primary"]["error"] === UPLOAD_ERR_OK)
				$primary = $this->data["Image"]["primary"];
			if (array_key_exists("secondary_1", $this->data["Image"]) and $this->data["Image"]["secondary_1"]["error"] === UPLOAD_ERR_OK)
				$secondary[0] = $this->data["Image"]["secondary_1"];
			if (array_key_exists("secondary_2", $this->data["Image"]) and $this->data["Image"]["secondary_2"]["error"] === UPLOAD_ERR_OK)
				$secondary[1] = $this->data["Image"]["secondary_2"];
			if (array_key_exists("secondary_3", $this->data["Image"]) and $this->data["Image"]["secondary_3"]["error"] === UPLOAD_ERR_OK)
				$secondary[2] = $this->data["Image"]["secondary_3"];
		}
		else
			return;

		// fill in gaps if this was originated from the 'add' page
		if ($from_add_page)
		{
			for ($i = 0; $i < count($secondary) - 1; $i++)
			{
				$left = $i;
				$right = $i+1;
				if ($secondary[$left] != null)
					continue;
				while($right < count($secondary) && $secondary[$left] == null && $secondary[$right] == null)
					$right ++;

				if ($right < count($secondary))
				{
					$secondary[$left] = $secondary[$right];
					$secondary[$right] = null;
				}
			}
		}

		$files = array();
		array_push($files, $primary);
		array_push($files, $secondary);

    	$errors = $this->Image->AddImage($listing_id, $files, $this->Session->read('user'));
    	$response = json_encode($errors);
    	$this->set('errors', $response);
    	return $response;
	}

	function add3($data = null)
	{
		$this->set('errors', null);
		$from_add_page = false;
		if (!$data)
		{
			$data = $this->data;
			$from_add_page = true;
		}

		$listing_id = 5; //TODO: update this
		$primary = null;
		$secondary = array();
		array_push($secondary, null);
		array_push($secondary, null);
		array_push($secondary, null);

		if ($this->data)
		{
			if (array_key_exists("primary", $this->data["Image"]) and $this->data["Image"]["primary"]["error"] === UPLOAD_ERR_OK)
				$primary = $this->data["Image"]["primary"];
			if (array_key_exists("secondary_1", $this->data["Image"]) and $this->data["Image"]["secondary_1"]["error"] === UPLOAD_ERR_OK)
				$secondary[0] = $this->data["Image"]["secondary_1"];
			if (array_key_exists("secondary_2", $this->data["Image"]) and $this->data["Image"]["secondary_2"]["error"] === UPLOAD_ERR_OK)
				$secondary[1] = $this->data["Image"]["secondary_2"];
			if (array_key_exists("secondary_3", $this->data["Image"]) and $this->data["Image"]["secondary_3"]["error"] === UPLOAD_ERR_OK)
				$secondary[2] = $this->data["Image"]["secondary_3"];
		}
		else
			return;

		// fill in gaps if this was originated from the 'add' page
		if ($from_add_page)
		{
			for ($i = 0; $i < count($secondary) - 1; $i++)
			{
				$left = $i;
				$right = $i+1;
				if ($secondary[$left] != null)
					continue;
				while($right < count($secondary) && $secondary[$left] == null && $secondary[$right] == null)
					$right ++;

				if ($right < count($secondary))
				{
					$secondary[$left] = $secondary[$right];
					$secondary[$right] = null;
				}
			}
		}

		$files = array();
		array_push($files, $primary);
		array_push($files, $secondary);

    	$errors = $this->Image->AddImage($listing_id, $files, $this->Session->read('user'));
    	$response = json_encode($errors);
    	$this->set('errors', $response);
    	return $response;
	}

	function edit($listing_id)
	{
		$this->set('errors', null);
		$this->setJsVar('edit_listing_id', $listing_id);
		$this->set('listing_id', $listing_id);
		$response = $this->add($this->data);
		$this->set('errors', $response);
	}

	function AddFromEditMenu()
	{
		CakeLog::write('debug', "params: " . print_r($this));
		//$listing_id = $this->params;
	}

	function LoadImages($listing_id)
	{
		$files = $this->Image->getImagesForListingId($listing_id);
		$folder_prefix = '/img/sublets/' . $listing_id . '/';
		$primary = null;
		$secondary = array();
		for ($i = 0; $i < count($files); $i++)
		{
			if (strrpos($files[$i], "primary") !== false)
				$primary = $folder_prefix . $files[$i];
			else
				array_push($secondary, $folder_prefix . $files[$i]);
		}

			sort($secondary);
			$this->set("primary", $primary);
			$this->set("secondary", $secondary);

			$return_files = array();
			array_push($return_files, $primary);
			array_push($return_files, $secondary);

			$this->layout = 'ajax';
			$this->set('response', json_encode($return_files));

		}

	function DeleteImage()
	{
		$file = null;
		$listing_id = $this->params[0]['listing_id'];
		$path = $this->params[0]['path'];
		$this->set('path', $path);
		$this->set('listing_id', $listing_id);
		CakeLog::write('debug', 'listing_id: ' . $listing_id . "; path: " . $path);
		$path = $this->Image->DeleteImage($listing_id, $path);
		$this->set('path', $path);
	}

	function uploadFile() 
	{
		$file = $this->data["Image"]["file"];
		//$file = $this->data["Image"]["listing_id"];
		$this->set('file', $file);
	  	if ($file["error"] === UPLOAD_ERR_OK) {
	    	$name = String::uuid(); 
	    	/* 
	    	TODO: ID NEEDS TO BE MODIFIED LATER AFTER ENTRY IS PUT INTO DB
	    	*/
	    	$file_path = WWW_ROOT."img/sublets/tmp/".$name;
	    	if (move_uploaded_file($file["tmp_name"], $file_path)) {
	    		$this->set("uploaded_img", "sublets/tmp/".$name);
	    		/*$this->Image->AddImage($file*/

		      /*$this->data["Image"]["id"] = $id;
		      $this->data["Image"]["user_id"] = $this->Session->read('user_id');
		      $this->data["Image"]["path"] = $file["name"];*/
		     /* $this->data[‘Upload’][‘filesize’] = $file[‘size’];
		      $this->data[‘Upload’][‘filemime’] = $file[‘type’];*/
		      return true;
	    	}
	  	}

	  	return false;
	}
}
