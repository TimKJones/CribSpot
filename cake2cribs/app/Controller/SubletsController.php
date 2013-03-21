<?php

class SubletsController extends AppController {
	public $helpers = array('Html', 'Js');
	public $uses = array();
    public $components= array('Session', 'Auth','RequestHandler');

    public function beforeFilter() {
        $this->Auth->allow('index');
        $this->Auth->allow('view');
        $this->Auth->allow('getSubletsAjax');
        $this->Auth->allow('userView');

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
        $canCreate = False;
        $universities = $this->Sublet->University->find('all');
        $buildingTypes = $this->Sublet->BuildingType->find('list');
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
            $savedUniversity = $this->Session->read('SubletInProgress.Sublet.university');
                    $this->set('savedUniversity', $savedUniversity);
                    $savedBuildingTypeID = $this->Session->read('SubletInProgress.Sublet.building_type_id');
                    $this->set('savedBuildingTypeID', $savedBuildingTypeID);
                    $savedName =  $this->Session->read('SubletInProgress.Sublet.name');
                    $this->set('savedName', $savedName);
        }
        
    }

    public function ajax_add2() {
    }

    public function ajax_add_create() {
        //add code to save mostly empty sublet 
        /*$this->Sublet->create($this->request->data);

        if ($this->Sublet->save($this->request->data, true, array('fieldList' => array('university_id', 'building_type_id', 'name'))))
        {
           /* $json = array('university_id' => $this->request->data['Sublet']['university_id'],
                    'building_type_id' => $this->Sublet->field('building_type_id'),
                    'name' => $this->Sublet->field('name'));
            //$json = json_encode($this->Sublet);
            //$this->set('response', $json);
            $error = array('IT went through');
            $json = json_encode($error);
            $this->set('response', $json);
        }
        else
        {
            //$error = $this->validateErrors($this->Sublet);
            $error = array('It did not go through.');
            $json = json_encode($error);
            $this->set('response', $json);

        }*/
        $this->Session->write('SubletInProgress', $this->request->data);
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

<<<<<<< HEAD

/*
Returns a list of marker_ids that will be visible based on the current filter settings.
*/
    public function ApplyFilter()
    {
        $this->UpdateFilterValues($this->params['url']);
        CakeLog::write("urlParams", print_r($this->params['url'], true));
        $response = $this->Sublet->getFilteredMarkerIdList($this->getSessionValues($this->params['url']));
        $this->layout = 'ajax';
        $this->set('response', $response);
    }

/*
Called via ajax when a marker is clicked to load all listings for that marker_id
Returns json encoded data.
*/
    public function LoadMarkerData($marker_id)
    {
        CakeLog::write("loadMarkerData", "marker_id = " . $marker_id);
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
        $maxPossibleBeds = 2;
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

=======
    
>>>>>>> b8eb1b2063bb33903e07537e0ee687f85dc6ede1
}
?>