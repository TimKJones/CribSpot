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
    $this->Auth->allow('APIGetBasicData');
  }

    public function index()
    {
        if(!$this->Auth->user() && !$this->Session->read('preferredUniversity'))
            return $this->redirect(array('controller' => 'landing', 'action' => 'index'));
        
        $school_id = $this->User->GetPreferredUniversity($this->Auth->user('id'));
        if ($school_id === null){
            $school_id = $this->Session->read('preferredUniversity');
            if ($school_id === null)
                return $this->redirect(array('controller' => 'landing', 'action' => 'index'));
        }

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
            $this->Session->write("currentUniversity", $school_name);
            $school_name = str_replace("_", " ", $school_name);
            $id = $this->University->getIdfromName($school_name);
            if ($id == null)
                throw new NotFoundException();

            /* store university id to enable 'back to map' button */
            if ($this->Auth->User('id') != null)
                $this->User->SavePreferredUniversity($this->Auth->User('id'), $id);
            else{
                $this->Session->write('preferredUniversity', $id); 
                CakeLog::write('writing session', $id);
            } 
            
            $this->set('school_id', $id);
            $university = $this->University->findById($id);
            if ($university == null)
                throw new NotFoundException();
            $this->set('university', $university);
        }
        
        $user = null;
        if($this->Auth->User()){
            $user = $this->User->getSafe($this->Auth->User('id'));
        }
        $this->set('school_name', $school_name);
        $this->set('user', json_encode($user));
        $this->set('locations', $this->University->getSchools());
        $this->set('user_years', $this->User->GetYears());
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

        $response = $this->_getBasicData($listing_type, $university_id);
        $this->set("response", $response);
    }

    /* ----------------------------------- iPhone API ------------------------------------- */
    public function APIGetBasicData($listing_type, $university_id)
    {
        header('Access-Control-Allow-Origin: *');
        $basicData = null;
        if (array_key_exists('token', $this->request->query) &&
            !strcmp($this->request->query['token'], Configure::read('IPHONE_API_TOKEN'))) {
            $basicData = $this->_getBasicData($listing_type, $university_id);
            $basicData = json_encode($basicData);
        }
    
        $this->set('response', $basicData);   
    }

    /* ------------------------------- private functions ------------------------------------ */
    private function _getBasicData($listing_type, $university_id)
    {
        $target_lat_long = Cache::read('universityTargetLatLong-'.$university_id, 'LongTerm');
        if ($target_lat_long === false){
            $target_lat_long = $this->University->getTargetLatLong($university_id);
            Cache::write('universityTargetLatLong-'.$university_id, $target_lat_long, 'LongTerm');
        }

        $basicData = Cache::read('mapBasicData-'.$university_id, 'MapData');
        if ($basicData === false){
            $basicData = $this->Listing->GetBasicData($listing_type, $target_lat_long, $this->Marker->RADIUS);
            Cache::write('mapBasicData-'.$university_id, $basicData, 'MapData');
        }
        
        $response = json_encode($basicData);
        return $response;
    }
}