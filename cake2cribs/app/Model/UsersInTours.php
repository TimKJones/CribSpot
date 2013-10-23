<?php 	

class UsersInTours extends AppModel {
	public $name = 'UsersInTours';
	public $belongsTo = array(
		'User' => array(
            'className'    => 'User',
            'foreignKey'   => 'user_id'
        ),
        'Tour' => array(
            'className'    => 'Tour',
            'foreignKey'   => 'tour_id'
        )
	);

	public $validate = array(
		'id' => 'numeric',
		'tour_id' => 'numeric',
		'user_id' => 'numeric', /* null if this user hasn't yet created a Cribspot account */
		'email' => array(
			'email' => array(
        		'rule'    => array('email', true),
        		'message' => 'Please supply a valid email address.'
    		)
		),
		'facebook_id' => 'numeric',
		'created' => 'datetime',
		'modified' => 'datetime'
	);
}

?>