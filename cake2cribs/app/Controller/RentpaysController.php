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
			if (!$this->request->is('ajax') && !Configure::read('debug') > 0)
				return;

			$this->layout = 'ajax';



			/* Initialize Braintree library */
			App::Import('Vendor', 'braintree/lib/Braintree');
			Braintree_Configuration::environment(Configure::read('BRAINTREE_ENVIRONMENT'));
			Braintree_Configuration::merchantId(Configure::read('BRAINTREE_MERCHANT_ID'));
			Braintree_Configuration::publicKey(Configure::read('BRAINTREE_PUBLIC_KEY'));
			Braintree_Configuration::privateKey(Configure::read('BRAINTREE_PRIVATE_KEY'));

			/* Create transaction using user payment info */
			$result = Braintree_Transaction::sale(array(
				'amount' => '1.00',
				'creditCard' => array(
					'number' => '4111111111111111',
					'expirationMonth' => '05',
					'expirationYear' => '15'
				)
			));

			if ($result->success) {
				CakeLog::write('braintree',"success!: " . $result->transaction->id);
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
