<?php
class MapController extends AppController {
  public $helpers = array('Html', 'GoogleMap', 'Js');
  public $components = array('RequestHandler', 'Session','Auth' => array(
        'authenticate' => array(
            'Form' => array(
                'fields' => array('username' => 'email')
                )
            )
        ));
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

    public function index()
    {
        if(!$this->Auth->user())
            return $this->redirect(array('controller' => 'landing', 'action' => 'index'));
        $school_id = $this->User->GetPreferredUniversity($this->Auth->user('id'));
        if ($school_id === null)
            return $this->redirect(array('controller' => 'landing', 'action' => 'index'));
        $school_name = $this->University->getNameFromId($school_id);
        $school_name = str_replace(" ", "_", $school_name);
        return $this->redirect(array('action' => 'rental', $school_name));
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
            if (is_numeric($school_name)){
                /* why are you going to sublets here? */
                $this->redirect(array('controller' => 'sublets', 'action' => 'show', $school_name));
            }
             
            $this->Session->write("currentUniversity", $school_name);
            $school_name = str_replace("_", " ", $school_name);
            $id = $this->University->getIdfromName($school_name);
            if ($id == null)
                throw new NotFoundException();

            /* store university id to enable 'back to map' button */
            if ($this->Auth->User('id') != null)
                $this->User->SavePreferredUniversity($this->Auth->User('id'), $id);
            else{
                CakeLog::write("user", print_r($this->Auth->User, true));
                $this->Session->write('preferredUniversity', $id); 
            } 
            
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
/* 
change names for building_type_id to string
Only return */

        $target_lat_long = $this->University->getTargetLatLong($school_id);
        $markers = $this->Marker->getAllMarkers($target_lat_long);
        $markerData = null;
        $this->layout = 'ajax';
        $this->set('response', $markers);
    }

    /*
    Loads the listing data necessary for the first marker click popup
    */
    public function GetBasicData($listing_type, $university_id)
    {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;

        $target_lat_long = $this->University->getTargetLatLong($university_id);
        $data = $this->Listing->GetBasicData($listing_type, $target_lat_long);
        $response = json_encode($data);
        $this->set("response", $response);
    }
}