<?php

class ImagesController extends AppController {
	public $helpers = array('Html', 'Js');
	public $uses = array('Image');
	public $components= array('RequestHandler', 'Auth', 'Session');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
		$this->Auth->allow('add');	
		$this->Auth->allow('LoadImages');
		$this->Auth->allow('GetPrimaryImages');
	}

	/*
	AJAX
	Pass image data to Image model to create table entries and move the file.
	Returns: SUCCESS: image_id of new image entry; FAILURE: error message
	*/
	function Add()
	{
		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
      		return;

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
		CakeLog::write('imagesuccess', print_r($imageResponse, true));
		$this->set('response', json_encode($imageResponse));
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
		$this->_setImagePathsForView($images, 'sml_');
		$this->set('response', json_encode($images));
	}

	/*
	Takes in an array of Image objects.
	Updates their image_paths by pre-pending prefix to their filenames.
	*/
	private function _setImagePathsForView(&$images, $prefix='lrg_')
	{
		foreach ($images as &$image){
			if (array_key_exists('Image', $image) && array_key_exists('image_path', $image['Image'])) {
				$fileName = $prefix.$this->_getFileNameFromPath($image['Image']['image_path']);
				$directory = $this->_getDeepestDirectoryFromPath($image['Image']['image_path']);
				$image['Image']['image_path'] = $directory.'/'.$fileName;
			}
		}
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
}
