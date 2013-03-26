<?php
class MapController extends AppController {
  public $helpers = array('Html', 'GoogleMap', 'Js');
  public $components = array('RequestHandler');
  public $uses = array('Marker', 'Listing', 'University', 'Sublet', 'BuildingType', 'BathroomType', 'GenderType', 'StudentType');

  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('LoadMarkers');
    $this->Auth->allow('index');
    $this->Auth->allow('sublet');
    $this->Auth->allow('ViewListing');
    $this->Auth->allow('LoadTypeTables');
    $this->Auth->allow('LoadHoverData');
  }

	public function index() {	
		$this->set('ListingTooltip', $this->Listing->getTooltipVariables());
		$this->InitFilterValues();
	}

	public function sublet($school_name = null, $address = null, $sublet_id = null)
	{
        /* -1 Code means do not open the tooltip */
		$marker_id_to_open = -1;
		$subletData = -1;

        if ($school_name != null)
        {
            $school_name = str_replace("_", " ", $school_name);
            $id = $this->University->getIdfromName($school_name);
            $this->set('school_id', $id);
            $lat_long = $this->University->getTargetLatLong($id);
            $this->set('school_lat', $lat_long['latitude']);
            $this->set('school_lng', $lat_long['longitude']);
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

		$this->InitFilterValues();
    CakeLog::write("sessionValues", "in map: " . print_r($this->Session->read(), true));
	}

  public function LoadTypeTables()
  {
    //CakeLog::write("sessionValues", "in loadTypeTables: " . print_r($this->getSessionValues(), true));
    $buildings = $this->BuildingType->LoadAll();
    $bathrooms = $this->BathroomType->LoadAll();
    $genders = $this->GenderType->LoadAll();
    $student_types = $this->StudentType->LoadAll();
    $response = array();
    array_push($response, $buildings, $bathrooms, $genders, $student_types);
    $this->layout = 'ajax';
    $this->set('response', json_encode($response));
  }

	public function ViewListing($listing_id = null)
	{
		if (!$listing_id)
			$this->redirect(array('controller' => 'map', 'action' => 'index'));
	}

	public function LoadMarkers($school_id) {
		$target_lat_long = $this->University->getTargetLatLong($school_id);
		$markers = $this->Marker->getAllMarkers($target_lat_long);
		$this->layout = 'ajax';
		$this->set('response', $markers);
	}

  public function LoadHoverData()
  {
    $hover_data = $this->Sublet->LoadHoverData();
    $this->layout = 'ajax';
    $response = json_encode($hover_data);
    $this->set("response", $response);
  }

  public function getSessionValues()
    {
        $sessionValues = array(
            'user_id' => $this->Auth->User('id'),
            'start_date' => $this->Session->read('start_date'),
            'end_date' => $this->Session->read('end_date'),
            'min_rent' => $this->Session->read('min_rent'),
            'max_rent' => $this->Session->read('max_rent'),
            'beds' => $this->Session->read('beds'),
            'house' => $this->Session->read('house'),
            'apt' => $this->Session->read('apt'),
            'unit_type_other' => $this->Session->read('unit_type_other'),
            'male' => $this->Session->read('male'),
            'female' => $this->Session->read('female'),
            'students_only' => $this->Session->read('students_only'),
            'grad' => $this->Session->read('grad'),
            'undergrad' => $this->Session->read('undergrad'),
            'bathroom_type' => $this->Session->read('bathroom_type'),
            'ac' => $this->Session->read('ac'),
            'parking' => $this->Session->read('parking'),
            'utilities_included' => $this->Session->read('utilities_included'),
            'no_security_deposit' => $this->Session->read('no_security_deposit'),
        );

        return $sessionValues;
    }
}