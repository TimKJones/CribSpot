<?php 

class GeocoderAddress extends AppModel {
	public $name = 'GeocoderAddress';
	public $primaryKey = 'id';
	public $actsAs = array('Containable');

	public function SaveAddress($csv_address, $geocoder_output)
	{
		$newAddress = array('GeocoderAddress' => array(
			'csv_address' => $csv_address,
			'geocoder_output' => $geocoder_output
		));

		$record = $this->_findGeocoderAddressByCSVAddress($csv_address);

		if ($record != null){
			$newAddress['GeocoderAddress']['id'] = $record['GeocoderAddress']['id'];
		}

		CakeLog::write('saving', print_r($newAddress, true));

		if (!$this->save($newAddress)){
			CakeLog::write('geocodersavefailed', print_r($this->validationErrors, true));
		}

		$log = $this->getDataSource()->getLog(false, false); 
	  	CakeLog::write("lastQuery", print_r($log, true));
	}

	public function GetGeocoderOutputFromAddress($csv_address)
	{
		$record = $this->_findGeocoderAddressByCSVAddress($csv_address);
		if ($record != null)
			return $record['GeocoderAddress']['geocoder_output'];

		return null;
	}

	private function _findGeocoderAddressByCSVAddress($address)
	{
		$record = $this->find('first', array(
			'conditions' => array('GeocoderAddress.csv_address' => $address)
		));

		return $record;
	}
}
