<?php 	

class Event extends AppModel {
	public $name = 'Event';
	public $primaryKey = 'id';
	public $actsAs = array('Containable');
	public $hasMany = array(
		'ContactEvent' => array(
            'className'    => 'ContactEvent',
            'foreignKey'   => 'event_id'
        )
	);

	public $validate = array(
		'id' => 'numeric',
		'event_type' => 'numeric',
		'created' => 'datetime',
		'modified' => 'datetime'
	);

	const CONTACT_EVENT = 0;
	public static function event_type_reverse($value = null) {
		$options = array(
			'Contact' => self::CONTACT_EVENT
		);
		return parent::StringToInteger($value, $options);
	}

	/*
	Saves an event.
	Assumes $event is in a form that can be saved with a save() call.
	*/
	public function Save($event)
	{
		CakeLog::write('eventsave', print_r($event, true));
		if (!$this->save($event)){
			$error = null;
			$error['Event'] = $event;
			$error['validationErrors'] = $this->validationErrors;
			$this->LogError($user_id, 66, $error);
		}
	}

}