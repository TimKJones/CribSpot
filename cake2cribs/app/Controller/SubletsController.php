<?php
class SubletsController extends AppController {
	public $helpers = array('Html', 'Js');
	public $uses = array();
    public $components= array('RequestHandler', 'Auth', 'Session');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index');
        $this->Auth->allow('view');
        $this->Auth->allow('getSubletsAjax');
        $this->Auth->allow('userView');
        $this->Auth->allow('ajax_add');
        $this->Auth->allow('ajax_add_create');
        $this->Auth->allow('ajax_add2');
        $this->Auth->allow('ApplyFilter');
        $this->Auth->allow('LoadMarkerData');
    }

	public function index() {
		$this->Sublet->recursive = 0;
        //$this->paginate['Sublet'] = array (
          //  'contain' => 'BuildingType');
		$this->set('sublets',$this->paginate('Sublet'));
	}
    public function getSubletsAjax() {

        $this->layout = 'ajax';
        $this->RequestHandler->setContent('json'); 
        $conditions  = array();
        /*if ($this->request->query['id'] != NULL)
        {
       //     $conditions['Sublet.user_id'] = $this->request->query['id'];
        }*/

        $sublets = $this->Sublet->find('all');
        $this->set('sublets', $sublets);
        $this->set('_serialize','sublets');
    }

    function show($sublet_id){
        $sublet = $this->Sublet->find('first', array('conditions'=>'Sublet.id='.$sublet_id));
        if($sublet == null){
            throw new NotFoundException();
        }
        $school_name = str_replace(" ", "_", $sublet['University']['name']);
        $address = str_replace(" ", "_", $sublet['Marker']['street_address']);
        $this->redirect("/map/sublet/$school_name/$address/$sublet_id");
    }

	public function view($id = null) {
		$this->Sublet->id = $id;
        $this->set('id', CakeSession::read("Auth.User.id"));
		if (!$this->Sublet->exists()) {
			throw new NotFoundException(__('Invalid sublet.'));
		}
		$this->set('sublet', $this->Sublet->read(null, $id));
	}

    public function userView() {
        $this->Sublet->recursive = 0;
    
    }

    public function manageSublets() {
        $this->User = $this->Auth->user();
        $this->set('id', CakeSession::read("Auth.User.id"));
        $this->set('sublets', $this->paginate());
    }
	public function add() {
        //have an action to fetch the current location to show in the map element
        $universities = $this->Sublet->University->find('threaded');

        $buildingTypes = $this->Sublet->BuildingType->find('list');
        $utilityTypes = $this->Sublet->UtilityType->find('list');
        $bathroomTypes = $this->Sublet->BathroomType->find('list');
        $paymentTypes = $this->Sublet->PaymentType->find('list');
        $this->set(compact('universities'));
        $this->set(compact('buildingTypes'));
        $this->set(compact('utilityTypes'));
        $this->set(compact('bathroomTypes'));
        $this->set(compact('paymentTypes'));

		if ($this->request->is('post')) {
            $uid = CakeSession::read("Auth.User.id");
			$this->Sublet->create();
            //add some crazy methods to detect likely building and stuff here
            // also add methods to choose marker if near other properties
			$this->request->data['Sublet']['user_id'] = $uid;
			if ($this->Sublet->save($this->request->data)) {
				$this->Session->setFlash(__('The sublet has been successfully addded'));

				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('An error occurred while adding the sublet. Please try again.'));
			}
		}
	}

    public function ajax_add() {
        Configure::write('debug', 2);
        $canCreate = False;
        $universities = $this->Sublet->University->find('all', array('fields' => array('id','name','city','state')));

        $buildingTypes = $this->Sublet->Marker->BuildingType->find('list');
        //$utilityTypes = $this->Sublet->UtilityType->find('list');
        //$bathroomTypes = $this->Sublet->BathroomType->find('list');
        //$paymentTypes = $this->Sublet->PaymentType->find('list');
        $this->set(compact('universities'));
        $this->set(compact('buildingTypes'));
        //$this->set(compact('utilityTypes'));
        //$this->set(compact('bathroomTypes'));
        //$this->set(compact('paymentTypes'));
         if($this->Auth->loggedIn())
        {
            $usersSublet = $this->Sublet->findByUserId($this->Auth->user('id'));
            if(!empty($usersSublet))
            {
                if($usersSublet['Sublet']['is_finished'] == 1)
                {
                    $this->redirect('/Sublet/edit?id='.$usersSublet['Sublet']['id']);
                }
                else
                {
                    $canCreate = False;
                    $savedUniversity = $this->Session->read('SubletInProgress.Sublet.university');
                    $this->set('savedUniversity', $savedUniversity);
                    $savedBuildingTypeID = $this->Session->read('SubletInProgress.Sublet.building_type_id');
                    $this->set('savedBuildingTypeID', $savedBuildingTypeID);
                    $savedName =  $this->Session->read('SubletInProgress.Sublet.name');
                    $this->set('savedName', $savedName);
                }
                
            }
            else
            {
                $canCreate = true;
            }
                $this->set('savedUniversity', $this->Session->read('SubletInProgress.Sublet.university'));
                $this->set('savedBuildingTypeID', $this->Session->read('SubletInProgress.Sublet.building_type_id'));
                $this->set('savedName', $this->Session->read('SubletInProgress.Sublet.name'));
                $this->set('savedAddress',$this->Session->read('SubletInProgress.Sublet.address'));
                $this->set('savedUnitNumber',$this->Session->read('SubletInProgress.Sublet.unit_number'));
                $this->set('university_id', $this->Session->read('SubletInProgress.Sublet.university_id'));
        }

        
    }

    public function ajax_add2() {
        $paymentTypes = $this->Sublet->PaymentType->find('list');
        $furnishedTypes = $this->Sublet->FurnishedType->find('list');
        $utilityTypes = $this->Sublet->UtilityType->find('list');
        $bathroomTypes = $this->Sublet->BathroomType->find('list');
        $this->set(compact('paymentTypes'));
        $this->set(compact('furnishedTypes'));
        $this->set(compact('utilityTypes'));
        $this->set(compact('bathroomTypes'));
        $this->set('savedDateBegin', $this->Session->read('SubletInProgress.Sublet.date_begin'));
        $this->set('savedDateEnd', $this->Session->read('SubletInProgress.Sublet.date_end'));
        $this->set('savedFlexibleDates', $this->Session->read('SubletInProgress.Sublet.flexible_dates'));
        $this->set('savedNumberBedrooms', $this->Session->read('SubletInProgress.Sublet.number_bedrooms'));
        $this->set('savedPricePerBedroom', $this->Session->read('SubletInProgress.Sublet.price_per_bedroom'));
        $this->set('savedPaymentTypeID', $this->Session->read('SubletInProgress.Sublet.payment_type_id'));
        $this->set('savedShortDescription', $this->Session->read('SubletInProgress.Sublet.short_description'));
        $this->set('savedNumberBathrooms', $this->Session->read('SubletInProgress.Sublet.number_bathrooms'));
        $this->set('savedBathroomTypeID', $this->Session->read('SubletInProgress.Sublet.bathroom_type_id'));
        $this->set('savedUtilityTypeID', $this->Session->read('SubletInProgress.Sublet.utility_type_id'));
        $this->set('savedUtilityCost', $this->Session->read('SubletInProgress.Sublet.utility_cost'));
        $this->set('savedParking', $this->Session->read('SubletInProgress.Sublet.parking'));
        $this->set('savedAC', $this->Session->read('SubletInProgress.Sublet.ac'));
        $this->set('savedFurnishedTypeID', $this->Session->read('SubletInProgress.Sublet.furnished_type_id'));
        $this->set('savedDepositAmount', $this->Session->read('SubletInProgress.Sublet.deposit_amount'));
        $this->set('savedAdditionalFeesDescription', $this->Session->read('SubletInProgress.Sublet.additional_fees_description'));
        $this->set('savedAdditionalFeesAmount', $this->Session->read('SubletInProgress.Sublet.additional_fees_amount'));




    }

    public function ajax_add3() {
        $studentTypes = $this->Sublet->Housemate->StudentType->find('list');
        $genderTypes = $this->Sublet->Housemate->GenderType->find('list');
        $this->set(compact('studentTypes'));
        $this->set(compact('genderTypes'));

        $this->set('savedHousemateQuantity', $this->Session->read('SubletInProgress.Housemate.quantity'));
        $this->set('savedHousemateEnrolled', $this->Session->read('SubletInProgress.Housemate.enrolled'));
        $this->set('savedHousemateStudentType', $this->Session->read('SubletInProgress.Housemate.student_type'));
        $this->set('savedHousemateMajor', $this->Session->read('SubletInProgress.Housemate.major'));
        $this->set('savedHousemateSeeking', $this->Session->read('SubletInProgress.Housemate.seeking'));
        $this->set('savedHousemateGender', $this->Session->read('SubletInProgress.Housemate.gender'));
        $this->set('savedDescription', $this->Session->read('SubletInProgress.Sublet.description'));
    }

    public function ajax_add_create() {

        if ($this->request->data['CurrentStep'] == 1)
        {
            $this->Session->write('SubletInProgress.Sublet.address', $this->request->data['Sublet']['address']);
            $this->Session->write('SubletInProgress.Sublet.building_type_id', $this->request->data['Sublet']['building_type_id']);
            $this->Session->write('SubletInProgress.Sublet.latitude', $this->request->data['Sublet']['latitude']);
            $this->Session->write('SubletInProgress.Sublet.longitude', $this->request->data['Sublet']['longitude']);
            $this->Session->write('SubletInProgress.Sublet.name', $this->request->data['Sublet']['name']);
            $this->Session->write('SubletInProgress.Sublet.unit_number', $this->request->data['Sublet']['unit_number']);
            $this->Session->write('SubletInProgress.Sublet.university', $this->request->data['Sublet']['university']);
            $this->Session->write('SubletInProgress.Sublet.university_id', $this->request->data['Sublet']['university_id']);
            $this->Session->write('SubletInProgress.Marker.street_address', $this->request->data['Sublet']['address']);
            $this->Session->write('SubletInProgress.Marker.city', $this->request->data['Marker']['city']);
            $this->Session->write('SubletInProgress.Marker.state', $this->request->data['Marker']['state']);
            $this->Session->write('SubletInProgress.Marker.zip', $this->request->data['Marker']['zip']);

        }
        else if ($this->request->data['CurrentStep'] == 2)
        {
            $this->Session->write('SubletInProgress.Sublet.date_begin', $this->request->data['Sublet']['date_begin']);
            $this->Session->write('SubletInProgress.Sublet.date_end', $this->request->data['Sublet']['date_end']);   
            $this->Session->write('SubletInProgress.Sublet.flexible_dates', $this->request->data['Sublet']['flexible_dates']);
            $this->Session->write('SubletInProgress.Sublet.number_bedrooms', $this->request->data['Sublet']['number_bedrooms']);
            $this->Session->write('SubletInProgress.Sublet.price_per_bedroom', $this->request->data['Sublet']['price_per_bedroom']);
            $this->Session->write('SubletInProgress.Sublet.payment_type_id', $this->request->data['Sublet']['payment_type_id']);
            $this->Session->write('SubletInProgress.Sublet.short_description', $this->request->data['Sublet']['short_description']);
            $this->Session->write('SubletInProgress.Sublet.number_bathrooms', $this->request->data['Sublet']['number_bathrooms']);
            $this->Session->write('SubletInProgress.Sublet.bathroom_type_id', $this->request->data['Sublet']['bathroom_type_id']);
            $this->Session->write('SubletInProgress.Sublet.utility_type_id', $this->request->data['Sublet']['utility_type_id']);
            $this->Session->write('SubletInProgress.Sublet.utility_cost', $this->request->data['Sublet']['utility_cost']);
            $this->Session->write('SubletInProgress.Sublet.parking', $this->request->data['Sublet']['parking']);
            $this->Session->write('SubletInProgress.Sublet.ac', $this->request->data['Sublet']['ac']);
            $this->Session->write('SubletInProgress.Sublet.furnished_type_id', $this->request->data['Sublet']['furnished_type_id']);
            $this->Session->write('SubletInProgress.Sublet.deposit_amount', $this->request->data['Sublet']['deposit_amount']);
            $this->Session->write('SubletInProgress.Sublet.additional_fees_description', $this->request->data['Sublet']['additional_fees_description']);
            $this->Session->write('SubletInProgress.Sublet.additional_fees_amount', $this->request->data['Sublet']['additional_fees_amount']);

        }
        else if ($this->request->data['CurrentStep'] == 3)
        {
            //http://book.cakephp.org/2.0/en/models/saving-your-data.html#model-savemany-array-data-null-array-options-array
            $this->Session->write('SubletInProgress.Housemate.quantity', $this->request->data['Housemate']['quantity']);
            $this->Session->write('SubletInProgress.Housemate.enrolled', $this->request->data['Housemate']['enrolled']);
            $this->Session->write('SubletInProgress.Housemate.student_type_id', $this->request->data['Housemate']['student_type_id']);
            $this->Session->write('SubletInProgress.Housemate.major', $this->request->data['Housemate']['major']);
            $this->Session->write('SubletInProgress.Housemate.seeking', $this->request->data['Housemate']['seeking']);
            $this->Session->write('SubletInProgress.Housemate.gender_type_id', $this->request->data['Housemate']['gender_type_id']);
            $this->Session->write('SubletInProgress.Sublet.description', $this->request->data['Sublet']['description']);
            if ($this->request->data['Finish'] !=0)
            {
                $this->Session->write('SubletInProgress.Sublet.user_id', $this->Auth->user('id'));
                //saving code here
                //STORE BUILDING TYPE ID IN MARKER AS WELL, MAKE IT NOT EDITABLE
                //find existing sublet/marker in database
                $sublet = $this->Sublet->find('first', array('conditions' => array('Sublet.user_id' => $this->Auth->user('id'))));
                if ($sublet)
                {
                    $this->set('response', "You already have one sublet");
                    return;
                }
                if($this->Sublet->save($this->Session->read('SubletInProgress')))
                {
                    //find marker address, if so, set marker ID to existing marker, if not, create one
                    $marker = $this->Sublet->Marker->find('first', array('conditions'=> array('Marker.street_address' => $this->Session->read('SubletInProgress.Sublet.address'))));
                    if ($marker)
                    {
                        $this->Sublet->saveField('marker_id', $marker['Marker']['marker_id']);
                    }
                    else
                    {
                        //insert new values into marker from session
                        $this->Session->write('SubletInProgress.Marker.building_type_id', $this->Session->read('SubletInProgress.Sublet.building_type_id'));
                        $this->Session->write('SubletInProgress.Marker.latitude', $this->Session->read('SubletInProgress.Sublet.latitude'));
                        $this->Session->write('SubletInProgress.Marker.longitude', $this->Session->read('SubletInProgress.Sublet.longitude'));
                        $this->Session->write('SubletInProgress.Marker.street_address', $this->Session->read('SubletInProgress.Sublet.address'));
                        $this->Session->write('SubletInProgress.Marker.alternate_name', $this->Session->read('SubletInProgress.Sublet.name'));
                        $this->set('response', array());
                        if($this->Sublet->Marker->save($this->Session->read('SubletInProgress.Marker')))
                        {
                            $json = json_encode($this->Session->read('SubletInProgress.Marker'));
                        }
                        else
                        {
                            $this->Sublet->Marker->set($this->Session->read('SubletInProgress.Marker'));
                        //check if passes email validation
                        $json = array('registerStatus' => 0,
                            'error' => 'Please check the fields below.');
                        $error = $this->validateErrors($this->Sublet->Marker);
                        $json = json_encode($error);
                        }
                        
                        
                        $this->set('response', $json);  
                        return;
                    }
                    $this->Session->write('SubletInProgress.Housemate.sublet_id', $this->Sublet->field('id'));
                    if ($this->Sublet->Housemate->save($this->Session->read('SubletInProgress')))
                    {
                        //if ($this->Sublet->Marker->hasAny(array('Marker.sublet_id')))

                    }
                    else
                    {
                        $this->set('response', array());
                        $this->Sublet->Housemate->set($this->Session->read('SubletInProgress'));
                        //check if passes email validation
                        $json = array('registerStatus' => 0,
                            'error' => 'Please check the fields below.');
                        $error = $this->validateErrors($this->Sublet->Housemate);
                        $json = json_encode($error);
                        $this->set('response', $json);  
                        return;
                    }
                     
                }
                {
                    $this->set('response', array());
                        $this->Sublet->set($this->Session->read('SubletInProgress'));
                        //check if passes email validation
                        $json = array('registerStatus' => 0,
                            'error' => 'Please check the fields below.');
                        $error = $this->validateErrors($this->Sublet);
                        $json = json_encode($error);
                        $this->set('response', $json);  
                        return;
                }
            //save sublet
            //save housemate
            }

        }
        $this->set('response',json_encode($this->Session->read('SubletInProgress')));
        
    }

	public function edit($id = null) {
        //check authentication on this, only user can edit their sublets
        //(and admin)
         //$universities = $this->Sublet->University->find('list');
        $buildingTypes = $this->Sublet->BuildingType->find('list');
        $utilityTypes = $this->Sublet->UtilityType->find('list');
        $bathroomTypes = $this->Sublet->BathroomType->find('list');
        $paymentTypes = $this->Sublet->PaymentType->find('list');
        //$this->set(compact('universities'));
        $this->set(compact('buildingTypes'));
        $this->set(compact('utilityTypes'));
        $this->set(compact('bathroomTypes'));
        $this->set(compact('paymentTypes'));
        //set current v

        $this->Sublet->id = $this->request->query['id'];
        if (!$this->Sublet->exists()) {
            throw new NotFoundException(__('Invalid sublet'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Sublet->save($this->request->data)) {
                $this->Session->setFlash(__('The sublet has been saved'));
                
            } else {
                $this->Session->setFlash(__('The sublet could not be saved. Please try again.'));
            }
        } else {
            $this->request->data = $this->Sublet->read(null, $id);

        }
    }

   
    //ADD ACCESS CONTROL FOR THE LOVE OF GOD
    public function delete($id = null) {

        //once again, check authentication

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Sublet->id = $id;
        if (!$this->Sublet->exists()) {
            throw new NotFoundException(__('Invalid sublet'));
        }
        if ($this->Sublet->delete()) {
            $this->Session->setFlash(__('Sublet deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Sublet was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

/*
Returns a list of marker_ids that will be visible based on the current filter settings.
*/
    public function ApplyFilter()
    {
        //CakeLog::write("sessionValues", 'before' . print_r($this->Session->read(), true));
        //$this->UpdateFilterValues($this->params['url']);
        //CakeLog::write("sessionValues", 'after' . print_r($this->getSessionValues(), true));
       //CakeLog::write("urlParams", print_r($this->params['url'], true));
        $response = $this->Sublet->getFilteredMarkerIdList($this->params['url']);
        $this->layout = 'ajax';
        $this->set('response', $response);
    }

/*
Called via ajax when a marker is clicked to load all listings for that marker_id
Returns json encoded data.
*/
    public function LoadMarkerData($marker_id)
    {
        $markerListingsData = $this->Sublet->getSubletDataByMarkerId($marker_id);
        $markerListingsData = json_encode($markerListingsData);
        $this->layout = 'ajax';
        $this->set('response', $markerListingsData);

        /*TODO: NEED THIS TO BE DONE AFTER RETURNING MARKER_LIST TO CLIENT */
        //$filter_id = $this->FilterAnalytic->AddFilter($this->getSessionValues(), $marker_id);
        //$this->ClickAnalytic->AddClick($this->Session->read('user'), $marker_id, $filter_id);
    }

    public function UpdateFilterValues($params)
    {
       /* start_date, end_date, minRent, maxRent, beds, house, apt, unit_type_other, male, female, students_only, grad, undergrad,
    bathroom_type, ac, parking, utilities_included, no_security_deposit*/
        /*
        If sliders are at either of these maximum values, ensure that results greater than the maximum value are also returned.
        */  
        $maxPossibleRent = 2000;

        if (array_key_exists("start_date", $params))
            $this->Session->write('start_date', $params['start_date']);
        if (array_key_exists("end_date", $params))
            $this->Session->write('end_date', $params['end_date']); 
        if (array_key_exists("min_rent", $params))
            $this->Session->write('min_rent', $params['min_rent']); 
        if (array_key_exists("max_rent", $params))
        {
            if ($params['max_rent'] == $maxPossibleRent)
                $params['max_rent'] = 999999;
            $this->Session->write('max_rent', $params['max_rent']); 
        }
        if (array_key_exists("beds", $params))
            $this->Session->write('beds', $params['beds']);
        if (array_key_exists("house", $params))
            $this->Session->write('house', $params['house'] == "true"); 
        if (array_key_exists("apt", $params))
            $this->Session->write('apt', $params['apt'] == "true"); 
        if (array_key_exists("unit_type_other", $params))
            $this->Session->write('unit_type_other', $params['unit_type_other'] == "true");  
        if (array_key_exists("male", $params))
            $this->Session->write('male', $params['male'] == "true");  
        if (array_key_exists("female", $params))
            $this->Session->write('female', $params['female'] == "true");  
        if (array_key_exists("students_only", $params))
            $this->Session->write('students_only', $params['students_only'] == "true");  
        if (array_key_exists("grad", $params))
            $this->Session->write('grad', $params['grad'] == "true"); 
        if (array_key_exists("undergrad", $params))
            $this->Session->write('undergrad', $params['undergrad'] == "true"); 
        if (array_key_exists("bathroom_type", $params))
            $this->Session->write('bathroom_type', $params['bathroom_type'] == "true"); 
        if (array_key_exists("ac", $params))
            $this->Session->write('ac', $params['ac'] == "true");
        if (array_key_exists("parking", $params))
            $this->Session->write('parking', $params['parking'] == "true");
        if (array_key_exists("utilities_included", $params))
            $this->Session->write('utilities_included', $params['utilities_included'] == "true");
        if (array_key_exists("no_security_deposit", $params))
            $this->Session->write('no_security_deposit', $params['no_security_deposit'] == "true");       
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
?>