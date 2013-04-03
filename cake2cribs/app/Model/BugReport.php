<?php 

class BugReport extends AppModel {
	public $belongsTo = array();
	public $validate = array(
		'id' => 'alphaNumeric'
	);

	public function newReport($data){
		$bug_report_data = array(
			'BugReport' =>array(
				'email' => $data['email'],
				'description' => $data['description'],
				'add_info' => $data['add_info'],
			)
		);
		if(!$this->save($bug_report_data)){
			die(debug($this->validationErrors));
		}		
	}
}
?>
