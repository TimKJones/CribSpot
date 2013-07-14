<?php

class ImagesController extends AppController {
	public $helpers = array('Html', 'Js');
	public $uses = array('Image');
	public $components= array('RequestHandler', 'Auth', 'Session');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
		$this->Auth->allow('add');
		$this->Auth->allow('AddImage');
		$this->Auth->allow('add2');
		$this->Auth->allow('add3');
		$this->Auth->allow('edit');
		$this->Auth->allow('LoadImages');
		$this->Auth->allow('DeleteImage');
		$this->Auth->allow('MakePrimary');
		$this->Auth->allow('SubmitCaption');

		$this->Auth->allow('add_test');
	}

	public function add_test(){}

	/*
	AJAX
	If SESSION[row_id] != $num_images, cleans up images left over from uploading in a previous tab (same session).
	Pass image data to Image model to create table entries and move the file.
	Appends image_id of new Image entry to SESSION[row_id]
	Returns: SUCCESS: image_id of new image entry; FAILURE: error message
	*/
	function AddImage()
	{
		$this->layout = 'ajax';
		if (!array_key_exists('form', $this->request->params) || 
			!array_key_exists('files', $this->request->params['form'])){
			/* TODO: log error */
			$this->set('response', json_encode(array('error' => 'Failed to upload image')));
		}

		$image = $this->request->params['form']['files'];
		$listing_id = null;
		if (array_key_exists('listing_id', $this->data))
			$listing_id = $this->data['listing_id'];

		$imageResponse = $this->Image->SaveImage($image, $this->_getUserId(), $listing_id);
		/* 
		If no listing_id is given, the listing entry for this image has not yet been created.
		Create an image entry and update its listing_id later after the listing has been completed.
		*/
		if ($listing_id == null && !array_key_exists('error', $imageResponse)){
			/* Initialize the array of image_ids for this row_id if it does not yet exist. */
			$image_id_array = $this->Session->read('row_' . $row_id);
			if ($image_id_array == null)
				$image_id_array = array();	

			/* 
			$imageResponse contains the new image_id if there was no error.
			Add this image_id to the list of image_ids belonging to this incomplete listing
			*/
			array_push($image_id_array, $imageResponse['image_id']);
			$this->Session->write('row_' . $row_id, $image_id_array);
		}

		$this->set('response', $imageResponse);
	}

	function add($data = null)
	{
		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;
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
		CakeLog::write('imageDebug', "data: " . print_r($data, true));
		$response = $this->Image->AddImage($data, $this->Auth->User('id'));
		$errors = $response['errors'];

		if (count($errors) == 0 && $this->request && $this->request->data && $this->request->data['imageSlot'])
		{
			$imageSlot = $this->request->data['imageSlot'];
			$this->Session->write("image" . $imageSlot, $filePath); 
			CakeLog::write('sessionDebug', $imageSlot . ": " . $filePath);
		}
		$this->layout = 'ajax';
		$this->set('response', json_encode($response));
	}

	function delete($photo_id)
	{
		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;
		$image = $this->Image->find('first', array(
			'fields' => array('user_id', 'image_path'), 
			'conditions' => array('Image.image_id' => $photo_id)
			)
		);
		if ($image['Image']['user_id'] == $this->Auth->user('id'))
		{
			$this->Image->delete($photo_id);
			unlink($image['Image']['image_path']);
			$response = array("success" => "You have successfully deleted this image!");
		}
		else
		{
			$response = array("errors" => "You do not own this image!");
		}
		$this->layout = 'ajax';
		$this->set('response', json_encode($response));
	}

	function edit($listing_id)
	{
		if ($listing_id == null)
			//TODO: show 404 page
			return;

		$this->set('errors', null);
		$this->setJsVar('edit_listing_id', $listing_id);
		$this->set('listing_id', $listing_id);
		$response = $this->add($this->data);
		$this->set('errors', $response);
	}

	function LoadImages($listing_id)
	{
		$images = $this->Image->getImagesForListingId($listing_id);
		$primary_image_index = $images[0] + 1; // in UI, index is offset by 1
		$files = $images[1];
		$captions = $images[2];

		$secondary = array();
		CakeLog::write("makePrimary", print_r($files, true));
		for ($i = 0; $i < count($files); $i++)
		{
			//CakeLog::write("loadingImages", "imageSlot" . $i ).
			$full_path = "/" . $files[$i];
			$next_slot = $i + 1;
			$this->Session->write('image' . $next_slot, $files[$i]); // get rid of the first slash
			array_push($secondary, $full_path);
		}

		$return_files = array();
		array_push($return_files, $primary_image_index);
		array_push($return_files, $secondary);
		array_push($return_files, $captions);

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
	//	CakeLog::write("makePrimary", print_r($this->Session, true));
		$path = $this->Session->read('image' . $image_slot);
		CakeLog::write('makePrimary', "selectedPath: " . $path);
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

	function SubmitCaption($caption, $image_slot)
	{
		CakeLog::write("addCaption", 'here0');
		$user_id = $this->Auth->user('id');
		CakeLog::write("addCaption", 'here1');
		//TODO: Change this
		if (!$user_id)
			$user_id = 0;
		CakeLog::write("addCaption", 'here2');
		$path = $this->Session->read('image' . $image_slot);
		CakeLog::write("addCaption", "adding caption: " . $caption . " | " . $image_slot . " | " . $path . " | " . $user_id);
		if (!$path)
			return false; // RETURN ERROR MESSAGE

		$response = $this->Image->SubmitCaption($caption, $user_id, $path);
		$this->layout = 'ajax';
		$this->set("response", $response);
	}
}
