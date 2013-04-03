<?php
class UtilityController extends AppController {
	public $helpers = array('Html', 'Js');
	public $uses = array('BugReport');
	public $components= array();


	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('sendBugReport');
	}

    public function sendBugReport(){
        if (!$this->request->is('post')){
            throw new NotFoundException();
        }

        $bug_report_data = $this->request->data;

        $this->BugReport->newReport($bug_report_data);
        $json_response = json_encode(array('success'=>true));
        $this->set('response', $json_response);


    }



}
?>