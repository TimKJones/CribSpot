<?php
class UniversityAdmin extends AppModel {
    public $name = 'UniversityAdmin';

    /*
	Returns the UniversityAdmin with the given user_id, or null if it doesnt exist
    */
    public function GetByUserId($user_id)
    {	
    	return $this->find('first', array(
    		'conditions' => array('user_id' => $user_id)
    	));
    }
}

?>