<?php
class SubletsController extends AppController {
	public $helpers = array('Html', 'Js');
	public $uses = array('Sublet', 'Marker', 'Housemate', 'University');
    public $components= array('RequestHandler', 'Auth', 'Session');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index');
        $this->Auth->allow('view');
        $this->Auth->allow('show');
        $this->Auth->allow('getSubletsAjax');
        $this->Auth->allow('userView');
        $this->Auth->allow('ajax_add');
        $this->Auth->allow('ajax_add_create');
        $this->Auth->allow('ajax_add2');
        $this->Auth->allow('ApplyFilter');
        $this->Auth->allow('LoadMarkerData');
        $this->Auth->allow('getSubletDataById');
        $this->Auth->allow('ajax_submit_sublet');
    }

    /*
        Retrieves sublet_data for $sublet_id
    */
    public function getSubletDataById($sublet_id)
    {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;

        $user_id = $this->Auth->User('id');
        if ($user_id == 0)
            $this->redirect(array('controller' => 'landing', 'action' => 'index'));

        if ($sublet_id == null)
            throw new NotFoundException();

        if (!$this->Sublet->UserOwnsSublet($user_id, $sublet_id))
            throw new NotFoundException(); 

         $this->Sublet->id = $sublet_id;
         $sublet_data = $this->Sublet->read();
         unset($sublet_data['User']['password']);
         unset($sublet_data['User']['password_reset_token']);
         unset($sublet_data['User']['vericode']);

         $this->layout = 'ajax';
         $this->set("response", json_encode($sublet_data));
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

    public function remove($sublet_id){
        $user_owns_sublet = $this->Sublet->UserOwnsSublet($this->Auth->User('id'), $sublet_id);
        if(!$user_owns_sublet){
            throw new NotFoundException();
        }
        $success = $this->Sublet->removeSublet($sublet_id);
        if(!$success){
            CakeLog::write('Sublets', 'Removing sublet $sublet_id failed. User: '. $this->Auth->User('id'));
            throw new NotFoundException();
        }
        // Need to change this to dashboard/listing or properties
        $this->redirect('/dashboard/');

    }

    public function ajax_add() {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;

        $universities = $this->Sublet->University->find('all', array('fields' => array('id','name','city','state', 'latitude', 'longitude')));
        $buildingTypes = $this->Sublet->Marker->BuildingType->find('list');
        $this->set(compact('universities'));
        $this->set(compact('buildingTypes'));
    }

    public function ajax_add2() {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;
        $paymentTypes = $this->Sublet->PaymentType->find('list');
        $furnishedTypes = $this->Sublet->FurnishedType->find('list');
        $utilityTypes = $this->Sublet->UtilityType->find('list');
        $bathroomTypes = $this->Sublet->BathroomType->find('list');
        $this->set(compact('paymentTypes'));
        $this->set(compact('furnishedTypes'));
        $this->set(compact('utilityTypes'));
        $this->set(compact('bathroomTypes'));
    }

    public function ajax_add3() {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;
        $studentTypes = $this->Sublet->Housemate->StudentType->find('list');
        $genderTypes = $this->Sublet->Housemate->GenderType->find('list');
        $this->set(compact('studentTypes'));
        $this->set(compact('genderTypes'));
    }

    public function ajax_add4()
    {

    }


    // TODO: This function is used to in sublet creation and sublet editing, it
    //       needs to be split into to distinct functions or else this function
    //       is going to be littered with if/else statements.
    //                                  -Mike Stratman (3/5/13)


    public function ajax_submit_sublet()
    {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;

        $this->layout = 'ajax';

        $sublet = $this->request->data['Sublet'];
        $marker = $this->request->data['Marker'];
        $housemate = $this->request->data['Housemate'];
        $sublet = $this->TranslateSubletTypeFields($sublet);
        $marker = $this->TranslateMarkerTypeFields($marker);
        $housemate = $this->TranslateHousemateTypeFields($housemate);

        CakeLog::write("savingSublet", print_r($sublet, true));
        CakeLog::write("savingMarker", print_r($marker, true));
        CakeLog::write("savingHousemate", print_r($housemate, true));

        $marker['building_type_id'] = $sublet['building_type_id']; // copy this over until we consolidate
                                                                   // to only storing in one table
        if ($sublet['id'] == null)
        {
            // this is a new posting
            unset($sublet['id']);
            unset($housemate['id']);
            unset($marker['id']);
        }
        else
        {
            // The user is editing a sublet
            // Verify that user owns sublet
            if (!$this->Sublet->UserOwnsSublet($this->Auth->User('id'), $sublet['id']))
            {
                $response = array('error' => 'Failed to save sublet. Error code: 1');
                $this->set('response', json_encode($response));
                return;
            }

            if ($housemate['id'] == null)
            {
                // We are editing a sublet, but no housemate_id was given
                $response = array('error' => 'Failed to save sublet. Error code: 2');
                $this->set('response', json_encode($response));
                return;
            }
            else
            {
                // verify that this housemate has sublet_id equal to the submitted sublet_id
                if (!$this->Housemate->BelongsToSubletId($housemate['id'], $sublet['id']))
                {
                    $response = array('error' => 'Failed to save sublet. Error code: 3');
                    $this->set('response', json_encode($response));
                    return;
                }
            }

            if(array_key_exists('marker_id', $marker) && $marker['marker_id'])
            {
                // Verify that the sublet with the submitted sublet_id has marker_id equal to the submitted marker_id
                if (!$this->Sublet->HasMarkerId($sublet['id'], $marker['marker_id']))
                {
                    $response = array('error' => 'Failed to save sublet. Error code: 4');
                    $this->set('response', json_encode($response));
                    return;
                }
            }
        }

        if (!array_key_exists('marker_id', $marker) || !$marker['marker_id'])
        {
            //Since there was no marker defined we need to find a marker
            //findMarker will create a marker if it doesn't find one by 
            //the street address passed in the marker
            $marker['marker_id'] = $this->Marker->FindMarkerId($marker);
        }


        $sublet_id = null;
        $housemate_id = null;
        $marker_id = $marker['marker_id'];
        $response = null;
        if ($marker_id != null)
        {
            $sublet['marker_id'] = $marker_id;
            $sublet['is_finished'] = 1;
            $sublet['user_id'] = $this->Auth->User('id');

            $sublet_id = $this->Sublet->SaveSublet($sublet);
            if ($sublet_id != null)
            {
                $housemate['sublet_id'] = $sublet_id;
                CakeLog::write("savingHousemate", print_r($housemate, true));
                $housemate_id = $this->Housemate->SaveHousemate($housemate);
            }
        }
        
        if ($marker_id == null || $sublet_id == null || $housemate_id == null)
        {
            $error_codes = '';
            if ($marker_id == null)
                $error_codes = $error_codes . '5 ';
            if ($sublet_id == null)
                $error_codes = $error_codes . '6 ';
            if ($marker_id == null)
                $error_codes = $error_codes . '7';

            $response = array('error' => 'There was an error saving your sublet. Contact help@cribspot.com if the error persists. Error code: ' . $error_codes);
        }
        else
        {
            $response = array('status' => 'Your sublet was saved successfully!');
            $response['newid'] = $sublet_id;
        }

        $this->set('response', json_encode($response));
    }

    public function ajax_add_create() {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;

        else if ($this->request->data['CurrentStep'] == 3)
        {
            if ($this->request->data['Finish'] !=0)
            {
                $this->Session->write('SubletInProgress.Sublet.user_id', $this->Auth->user('id'));
                //saving code here
                //STORE BUILDING TYPE ID IN MARKER AS WELL, MAKE IT NOT EDITABLE
                //find existing sublet/marker in database
                $sublet = $this->Sublet->find('first', array('conditions' => array('Sublet.user_id' => $this->Auth->user('id'))));
                if ($sublet)
                {
                    $this->Sublet->id = $sublet['Sublet']['id'];
                    $this->Sublet->save($this->Session->read('SubletInProgress'));
                    $housemate = $this->Sublet->Housemate-> find('first', array('conditions' => array('Housemate.sublet_id' => $this->Sublet->id)));
                    $this->Sublet->Housemate->id = $housemate['Housemate']['id'];
                    $this->Sublet->Housemate->Save($this->Session->read('SubletInProgress'));

                    $this->set('response', json_encode(array('status' => 'Your existing sublet was updated.')));
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
            $this->set('response',json_encode(array('status' => 'You successfully saved your sublet.')));
            return;
        }
        $this->set('response',json_encode($this->Session->read('SubletInProgress')));
        
    }
    
/*
Returns a list of marker_ids that will be visible based on the current filter settings.
*/
    public function ApplyFilter()
    {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;
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
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;
        $markerListingsData = $this->Sublet->getSubletDataByMarkerId($marker_id);
        $markerListingsData = json_encode($markerListingsData);
        $this->layout = 'ajax';
        $this->set('response', $markerListingsData);

        /*TODO: NEED THIS TO BE DONE AFTER RETURNING MARKER_LIST TO CLIENT */
        //$filter_id = $this->FilterAnalytic->AddFilter($this->getSessionValues(), $marker_id);
        //$this->ClickAnalytic->AddClick($this->Session->read('user'), $marker_id, $filter_id);
    }


    public function TranslateHousemateTypeFields($housemate)
    {
        $newHousemate = $housemate;
        $newHousemate['student_type_id'] = $this->GetTypeId('StudentType', $housemate['student_type_id']);
        $newHousemate['gender_type_id'] = $this->GetTypeId('GenderType', $housemate['gender_type_id']);
        return $newHousemate;
    }

    public function TranslateSubletTypeFields($sublet)
    {
        $newSublet = $sublet;
        $newSublet['building_type_id'] = $this->GetTypeId('BuildingType', $sublet['building_type_id']);
        $newSublet['bathroom_type_id'] = $this->GetTypeId('BathroomType', $sublet['bathroom_type_id']);
        $newSublet['utility_type_id'] = $this->GetTypeId('UtilityType', $sublet['utility_type_id']);
        $newSublet['furnished_type_id'] = $this->GetTypeId('FurnishedType', $sublet['furnished_type_id']);
        return $newSublet;
    }

    public function TranslateMarkerTypeFields($marker)
    {
        $newMarker = $marker;
        //$newMarker['building_type_id'] = 
        return $newMarker;  
    }

    public function GetTypeId($table, $string)
    {
        $studentTypes = null;
        if ($table == 'StudentType')
        {
            if ($studentTypes = Cache::read('StudentTypes') === false)
            {
                $this->Sublet->Housemate->StudentType->contain();
                $studentTypes = $this->Sublet->Housemate->StudentType->find('all');
                Cache::write('StudentTypes', $studentTypes);
                CakeLog::write("studentTypesCache", print_r($studentTypes, true));
            }

            return $this->TypeTableArraySearch($string, $studentTypes, 'StudentType');
        }

        $genderTypes = null;
        if (($table == 'GenderType' && $genderTypes = Cache::read('GenderTypes')) === false)
        {
            $this->Sublet->Housemate->GenderType->contain();
            $genderTypes = $this->Sublet->Housemate->GenderType->find('all');
            Cache::write('GenderTypes', $genderTypes);
            return $this->TypeTableArraySearch($string, $genderTypes, 'GenderType');
        }

        $buildingTypes = null;
        if ($table == 'BuildingType')
        {
            if ($buildingTypes = Cache::read('BuildingTypes') === false)
            {
                $this->Sublet->Marker->BuildingType->contain();
                $buildingTypes = $this->Marker->BuildingType->find('all');
                Cache::write('BuildingTypes', $buildingTypes);
            }

            return $this->TypeTableArraySearch($string, $buildingTypes, 'BuildingType');
        }

        $bathroomTypes = null;
        if (($table == 'BathroomType' && $bathroomTypes = Cache::read('BathroomTypes')) === false)
        {
            $this->Sublet->BathroomType->contain();
            $bathroomTypes = $this->Sublet->BathroomType->find('all');
            Cache::write('BathroomTypes', $bathroomTypes);
            return $this->TypeTableArraySearch($string, $bathroomTypes, 'BathroomType');
        }

        $utilityTypes = null;
        if (($table == 'UtilityType' && $utilityTypes = Cache::read('UtilityTypes')) === false)
        {
            $this->Sublet->UtilityType->contain();
            $utilityTypes = $this->Sublet->UtilityType->find('all');
            Cache::write('UtilityTypes', $utilityTypes);
            return $this->TypeTableArraySearch($string, $utilityTypes, 'UtilityType');
        }

        $furnishedTypes = null;
        if (($table == 'FurnishedType' && $furnishedTypes = Cache::read('FurnishedTypes')) === false)
        {
            $this->Sublet->FurnishedType->contain();
            $furnishedTypes = $this->Sublet->FurnishedType->find('all');
            Cache::write('FurnishedTypes', $furnishedTypes);
            return $this->TypeTableArraySearch($string, $furnishedTypes, 'FurnishedType');
        }
        
        return -1;
    }

    /*
    returns the id of $string in $tableData; -1 if not found.
    */
    public function TypeTableArraySearch($string, $tableData, $tableName)
    {
        CakeLog::write("TypeTableArraySearch", print_r($tableData, true));
        for ($i = 0; $i < count($tableData); $i++)
        {
            if ($tableData[$i][$tableName]['name'] == $string)
                return $tableData[$i][$tableName]['id'];
            else
                CakeLog::write("TypeTableArraySearch", $tableData[$i][$tableName]['name'] . " != " . $string);
        }

        return -1;
    }

    /*
    Stores current filter values in session.
    This is not currently called, but should eventually for logging analytics, as well 
    as for a more efficient filtering process.
    */
    public function UpdateFilterValues($params)
    {
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