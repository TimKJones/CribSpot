<?php
class MapController extends AppController {
  public $helpers = array('Html', 'GoogleMap', 'Js');
  public $components = array('RequestHandler');
  public $uses = array('Marker', 'Listing', 'University', 'Sublet', 'BuildingType', 'BathroomType', 'GenderType', 'StudentType', 'User');

  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('LoadMarkers');
    $this->Auth->allow('index');
    $this->Auth->allow('sublet');
    $this->Auth->allow('rental');
    $this->Auth->allow('ViewListing');
    $this->Auth->allow('LoadTypeTables');
    $this->Auth->allow('LoadHoverData');
    $this->Auth->allow('GetBasicData');
  }

    /*
    Action for main sublet map page
    */
    public function sublet($school_name = null, $address = null, $sublet_id = null)
    {
        if ($school_name == null)
            $this->redirect(array('controller' => 'landing', 'action' => 'index'));

        /* -1 Code means do not open the tooltip */
        if (($address == null && $sublet_id != null) || 
            ($address != null && $sublet_id == null))
            throw new NotFoundException();
        
        $marker_id_to_open = -1;
        $subletData = -1;

        if ($school_name != null)
        {
            if (is_numeric($school_name))
                $this->redirect(array('controller' => 'sublets', 'action' => 'show', $school_name));
            $this->Session->write("currentUniversity", $school_name);
            $school_name = str_replace("_", " ", $school_name);
            $id = $this->University->getIdfromName($school_name);
            if ($id == null)
                throw new NotFoundException();  
            $this->set('school_id', $id);
            $lat_long = $this->University->getTargetLatLong($id);
            if ($lat_long == null)
                throw new NotFoundException();
            $this->set('school_lat', $lat_long['latitude']);
            $this->set('school_lng', $lat_long['longitude']);
            $this->set('school_city', $lat_long['city']);
            $this->set('school_state', $lat_long['state']);
            $this->set('school_name', $school_name);
        }

        if ($sublet_id != null)
        {
            $this->set("listing_id_to_open", $sublet_id);
            $subletData = $this->Sublet->getSubletData($sublet_id);
            if (array_key_exists("Sublet", $subletData) && array_key_exists("marker_id", $subletData['Sublet']))
                $marker_id_to_open = $subletData['Sublet']['marker_id'];
            if ($subletData == null)
                $marker_id_to_open = -2;
        }

        $this->set("marker_id_to_open", $marker_id_to_open);
        $this->set("sublet_data_for_tooltip", $subletData);
        $user = null;
        if($this->Auth->User()){
            $user = $this->User->getSafe($this->Auth->User('id'));
        }
        $this->set('user', json_encode($user));
        $this->InitFilterValues();
    }

    /*
    Action for main map page for rentals
    */
    public function rental($school_name = null)
    {
        if ($school_name == null)
            $this->redirect(array('controller' => 'landing', 'action' => 'index'));
        
        $marker_id_to_open = -1;
        $subletData = -1;
        
        $this->set('active_listing_type', 'rental');

        if ($school_name != null)
        {
            if (is_numeric($school_name))
                $this->redirect(array('controller' => 'sublets', 'action' => 'show', $school_name));
            $this->Session->write("currentUniversity", $school_name);
            $school_name = str_replace("_", " ", $school_name);
            $id = $this->University->getIdfromName($school_name);
            if ($id == null)
                throw new NotFoundException();  
            $this->set('school_id', $id);
            $lat_long = $this->University->getTargetLatLong($id);
            if ($lat_long == null)
                throw new NotFoundException();
            $this->set('school_lat', $lat_long['latitude']);
            $this->set('school_lng', $lat_long['longitude']);
            $this->set('school_city', $lat_long['city']);
            $this->set('school_state', $lat_long['state']);
            $this->set('school_name', $school_name);
        }
        
        $user = null;
        if($this->Auth->User()){
            $user = $this->User->getSafe($this->Auth->User('id'));
        }
        $this->set('user', json_encode($user));
        $this->InitFilterValues();
    }

    public function ViewListing($listing_id = null)
    {
        if (!$listing_id)
            $this->redirect(array('controller' => 'map', 'action' => 'index'));
    }

    /*
    Loads all marker data to return via ajax to client.
    */
    public function LoadMarkers($school_id, $listing_type) {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;

        $target_lat_long = $this->University->getTargetLatLong($school_id);
        $markers = $this->Marker->getAllMarkers($target_lat_long);
        $markerData = null;
        $this->layout = 'ajax';
        $this->set('response', $markers);
    }

    /*
    Loads the listing data necessary for the first marker click popup
    */
    public function GetBasicData($listing_type)
    {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;

        $data = $this->Listing->GetBasicData($listing_type);
        $response = json_encode($data);
        $this->set("response", $response);
    }
}