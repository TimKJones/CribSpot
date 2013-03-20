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
        parent::beforeFilter();
        //$this->Auth->allow('manageSublets');
        //$this->Auth->allow('add');
        //$this->Auth->allow('ajax_add');
        //$this->Auth->allow('edit');
        //$this->Auth->allow('delete');
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
        $universities = $this->Sublet->University->find('list');
        $buildingTypes = $this->Sublet->BuildingType->find('list');
        $utilityTypes = $this->Sublet->UtilityType->find('list');
        $bathroomTypes = $this->Sublet->BathroomType->find('list');
        $paymentTypes = $this->Sublet->PaymentType->find('list');
        //$this->set(compact('universities'));
        $this->set(compact('buildingTypes'));
        $this->set(compact('utilityTypes'));
        $this->set(compact('bathroomTypes'));
        $this->set(compact('paymentTypes'));
        
    }

    public function ajax_add2() {
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

    
}
?>