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
		$this->Auth->allow('GetPrimaryImages');

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
			return;
		}

		$image = $this->request->params['form']['files'];
		$listing_id = null;
		if (array_key_exists('listing_id', $this->data))
			$listing_id = $this->data['listing_id'];

		$imageResponse = $this->Image->SaveImage($image, $this->_getUserId(), $listing_id);
		$this->set('response', json_encode($imageResponse));
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

		$response = $this->Image->AddImage($data, $this->Auth->User('id'));
		$errors = $response['errors'];

		if (count($errors) == 0 && $this->request && $this->request->data && $this->request->data['imageSlot'])
		{
			$imageSlot = $this->request->data['imageSlot'];
			$this->Session->write("image" . $imageSlot, $filePath); 
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
		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;
		
		$images = $this->Image->getImagesForListingId($listing_id);
		$primary_image_index = $images[0] + 1; // in UI, index is offset by 1
		$files = $images[1];
		$captions = $images[2];

		$secondary = array();
		for ($i = 0; $i < count($files); $i++)
		{
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
		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;

		$file = null;
		$listing_id = $this->listing_id;
		$image_slot = $this->params[0]['image_slot'];
		$path = $this->Session->read('image' . $image_slot);
		$this->set('path', $path);
		$this->set('listing_id', $listing_id);
		$response = $this->Image->DeleteImage($this->Auth->user('id'), $listing_id, $path);
		if ($response == "DELETE_SUCCESSFUL")
			$this->Session->write("image" . $image_slot, null);

		$this->set('error', $response);
	}

	function MakePrimary($image_slot)
	{
		$path = $this->Session->read('image' . $image_slot);
		$this->Image->MakePrimary($this->listing_id, $path);
	}

	/*
	returns the primary images for the specified listing ids
	*/
	public function GetPrimaryImages($listing_ids)
	{
		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;

		$this->layout = 'ajax';
		$listing_ids = json_decode($listing_ids);
		$images = $this->Image->GetPrimaryImagesByListingIds($listing_ids);
		$this->set('response', json_encode($images));
	}
}
