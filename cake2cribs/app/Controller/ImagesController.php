<?php

class ImagesController extends AppController {
	public $helpers = array('Html', 'Js');
	public $uses = array('Image');
	var $listing_id = 5;

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
		//CakeLog::write('debug', "fileuploadDebug: " . print_r($this->request->data['imageSlot'], true));
		//CakeLog::write('fileDebug', "params3: " . print_r($this, true));
		$this->set('errors', null);
		$from_add_page = false;
		if (!$data)
		{ // function is being called from a form submit (rather than from the edit function)
			if (array_key_exists('form', $this->request->params) && array_key_exists('files', $this->request->params['form']))
			{
				$data = $this->request->params['form']['files'];
				$from_add_page = true;
			}
		}
    	//CakeLog::write('imageDebug', "data: " . print_r($data, true));
		//CakeLog::write("fileDebug", print_r($data, true));
    	$response = $this->Image->AddImage($this->listing_id, $data, $this->Session->read('user'));
    	$errors = $response[0];
    	$filePath = $response[1];

    	if (count($errors) == 0 && $this->request && $this->request->data && $this->request->data['imageSlot'])
    	{
    		$imageSlot = $this->request->data['imageSlot'];
    		$this->Session->write("image" . $imageSlot, $filePath); 
    		CakeLog::write('sessionDebug', $imageSlot . ": " . $filePath);
    	}

    	$errors = json_encode($errors);
    	$this->set('errors', $errors);
    	return $errors;
	}

	function edit($listing_id)
	{
		$this->set('errors', null);
		$this->setJsVar('edit_listing_id', $listing_id);
		$this->set('listing_id', $listing_id);
		$response = $this->add($this->data);
		$this->set('errors', $response);
	}

	function edit2($listing_id)
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
		$primary_image_index = null;
		$primary_image_index = 1; // TODO: get this from db
		$secondary = array();
		for ($i = 0; $i < count($files); $i++)
		{
			array_push($secondary, $folder_prefix . $files[$i]);
		}

		$return_files = array();
		array_push($return_files, $primary_image_index);
		array_push($return_files, $secondary);

		$this->layout = 'ajax';
		$this->set('response', json_encode($return_files));

		}

	function DeleteImage()
	{
		CakeLog::write('imageDebug', 'deleting');
		CakeLog::write('imageDebug', 'params: ' . print_r($this->params[0], true));
		$file = null;
		$listing_id = $this->listing_id;
		$image_slot = $this->params[0]['image_slot'];
		$path = $this->Session->read('image' . $image_slot);
		CakeLog::write('imageDebug', $path);
		$this->set('path', $path);
		$this->set('listing_id', $listing_id);
		//CakeLog::write('imageDebug', 'listing_id: ' . $listing_id . "; path: " . $path);
		$response = $this->Image->DeleteImage($this->Auth->user('id'), $listing_id, $path);
		if ($response == "DELETE_SUCCESSFUL")
			$this->Session->write("image" . $image_slot, null);

		CakeLog::write('imageDebug', 'error code: ' . $response);
		$this->set('error', $response);
	}

	function MakePrimary($image_slot)
	{
		$path = $this->Session->read('image' . $image_slot);
		$this->Image->MakePrimary($this->listing_id, $path);
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
