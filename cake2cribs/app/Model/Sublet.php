<?php 

class Sublet extends AppModel {
    public $name = 'Sublet';
    public $primaryKey = 'sublet_id';
    public $belongsTo = array(
        'Listing' => array(
            'className'    => 'Listing',
            'foreignKey'   => 'listing_id'
        )   
    );
    public $actsAs = array('Containable');
    public $validate = array(
        'sublet_id' => 'numeric',
        'listing_id' => 'numeric',
        'rent' => array(  /*this is total rent, not per person */
                'numeric' => array(
                        'rule' => 'numeric',
                        'required' => false
                )
        ),
        'beds' => array(
                'numeric' => array(
                        'rule' => 'numeric',
                        'required' => false
                )
        ),
        'baths' => 'decimal',
        'bathroom_type' => 'numeric',
        'parking_available' => 'boolean',
        'parking_description' => array(
                'between' => array(
                        'rule' => array('between',0,1000),
                        'message' => 'Must be less than 100 characters'
                )
        ),
        'utilities_included' => 'boolean',
        'utilities_description' => array(
                'between' => array(
                        'rule' => array('between',0,1000),
                        'message' => 'Must be less than 100 characters'
                )
        ),
        'start_date' => array(
                'date' => array(
                        'rule' => array('date', 'ymd'),
                        'required' => false
                )
        ),
        'end_date' => array(
                'date' => array(
                        'rule' => array('date', 'ymd'),
                        'required' => false
                )
        ),
        'available_now' => 'boolean',
        'deposit' => 'numeric',
        'air' => 'boolean',
        'furnished_type' => 'numeric',
        'description' => array(
                'between' => array(
                        'rule' => array('between',0,10000),
                        'message' => 'Must be less than 10000 characters'
                )
        ),
        'shared_type' => array( /* Specifies whether or not this unit will be shared with other parties */
            'numeric' => array(
                'rule' => 'numeric',
                'required' => false
            )
        ),
        'washer_dryer' => 'numeric', 
    );

    public $MAX_BEDS = 4;
    public $MAX_RENT = 1000;
    public $FILTER_FIELDS = array(
        'ParkingAvailable' => array('Boolean' => array('parking_type', 0, 'Sublet')),
        'Beds' => array('MultipleOption'=>array('beds', 7, 'Sublet')), /* 7 is MAX_BEDS */
        'Rent' => array('Range' => array('rent', 1000, 'Sublet')), /* 1000 is MAX_RENT */
        'StartDate' => array('DatePicker' => array('start_date', '>', 'Sublet')), 
        'EndDate' => array('DatePicker' => array('end_date', '<', 'Sublet')),
        'SharedUnit' => array('MultipleOption'=>array('shared_type', 1, 'Sublet')),
        'SharedBath' => array('MultipleOption'=>array('bathroom_type', 1, 'Sublet')),
        'UnitTypes' => array('MultipleOption' => array('building_type_id', 3 /* Rental::BUILDING_TYPE_CONDO */, 'Marker')),
    );

    /* ---------- unit_style_options ---------- */
    const BATHROOM_TYPE_SHARED = 0;
    const BATHROOM_TYPE_PRIVATE = 1;

    public static function bathroom_type($value = null) {
        $options = array(
            self::BATHROOM_TYPE_PRIVATE => __('Private', true),
            self::BATHROOM_TYPE_SHARED => __('Shared', true)
        );
        return parent::enum($value, $options);
    }

    public static function bathroom_type_reverse($value = null) {
        $options = array(
            'Private' => self::BATHROOM_TYPE_PRIVATE,
            'Shared' => self::BATHROOM_TYPE_SHARED
        );
        return parent::StringToInteger($value, $options);
    }

        /* ---------- shared_type ---------- */
    const SHARED_TYPE_SHARED = 0;
    const SHARED_TYPE_NOT_SHARED = 1;

    public static function shared_type($value = null) {
        $options = array(
            self::SHARED_TYPE_SHARED => __('Shared', true),
            self::SHARED_TYPE_NOT_SHARED => __('Not Shared', true)
        );
        return parent::enum($value, $options);
    }

    public static function shared_type_reverse($value = null) {
        $options = array(
            'Shared' => self::SHARED_TYPE_SHARED,
            'Not Shared' => self::SHARED_TYPE_NOT_SHARED
        );
        return parent::StringToInteger($value, $options);
    }
};