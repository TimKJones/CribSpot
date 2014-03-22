<?php
class RentpaysController extends AppController {
    public $helpers = array('Html');
    public $components = array('Auth');
    public $uses = array('RentpayUser');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('Signup');
        $this->Auth->allow('CreateTransaction');
		}

		public function Signup() {
		}

		public function CreateTransaction() {
			if ((!$this->request->is('ajax') && !Configure::read('debug') > 0) || !$this->request->isPost())
				return;

			$this->layout = 'ajax';

			/* Initialize Braintree library */
			App::Import('Vendor', 'braintree/lib/Braintree');
			Braintree_Configuration::environment(Configure::read('BRAINTREE_ENVIRONMENT'));
			Braintree_Configuration::merchantId(Configure::read('BRAINTREE_MERCHANT_ID'));
			Braintree_Configuration::publicKey(Configure::read('BRAINTREE_PUBLIC_KEY'));
			Braintree_Configuration::privateKey(Configure::read('BRAINTREE_PRIVATE_KEY'));

			CakeLog::write('braintree_params', print_r($this->request->data, true));
			$params = $this->request->data;
			/* Set local variables for params */
			$first_name = $params['first_name'];
			$last_name = $params['last_name'];
			$email = $params['email'];
			$amount = $params['amount'];
			$number = $params['number'];
			$cvv = $params['cvv'];
			$expirationMonth = $params['month'];
			$expirationYear = $params['year'];

			$result = Braintree_Customer::create(array(
				'firstName' => $first_name,
				'lastName' => $last_name,
				'email' => $email,
				'customFields' => array(
					"street_address" => "808 Brown St",
					'pm_name' => 'PMSI',
					'building_name' => 'Zaragon'
				),
				'creditCard' => array(
					'number' => $number,
					'expirationMonth' => $expirationMonth,
					'expirationYear' => $expirationYear,
					'cvv' => $cvv
				)
			));

			/* Create transaction using user payment info */
			/*$result = Braintree_Transaction::sale(array(
				'amount' => $params['amount'],
				'creditCard' => array(
					'number' => $params['number'],
					'expirationMonth' => $params['month'],
					'expirationYear' => $params['year']
				)
			));*/

			if ($result->success) {
				CakeLog::write('braintree',print_r($result, true));
			} else if ($result->transaction) {
				CakeLog::write('braintree',"Error processing transaction:");
				CakeLog::write('braintree',"\n  message: " . $result->message);
				CakeLog::write('braintree',"\n  code: " . $result->transaction->processorResponseCode);
				CakeLog::write('braintree',"\n  text: " . $result->transaction->processorResponseText);
			} else {
				CakeLog::write('braintree',"Message: " . $result->message);
				CakeLog::write('braintree',"\nValidation errors: \n");
				CakeLog::write('braintree',$result->errors->deepAll());
			}
		}
}
