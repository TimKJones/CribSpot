<?php
class RentpaysController extends AppController {
    public $helpers = array('Html');
    public $components = array('Auth');

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
			/* User information */
			$first_name = $params['first_name'];
			$last_name = $params['last_name'];
			$email = $params['email'];
			$pm_name = 'PMSI';
			$street_address = '808 Brown St';

			/* Payment information */
			$amount = $params['amount'];
			$number = $params['number'];
			$cvv = $params['cvv'];
			$expirationMonth = $params['month'];
			$expirationYear = $params['year'];
			$is_venmo = 'true';
			$housemates = array(
				array('email' => 'tim@cribspot.com', 'rent' => 1541),	
				array('email' => 'evan@cribspot.com', 'rent' => 1400)
			);
			$housemateString = '';
			foreach ($housemates as $housemate){
				$housemateString .= $housemate['email'].'-';
				if (array_key_exists('rent', $housemate))
					$housemateString .= "$".$housemate['rent'];
				else
					$housemateString .= '$NA';

				$housemateString .= ', ';
			}

			$result = Braintree_Customer::create(array(
				'firstName' => $first_name,
				'lastName' => $last_name,
				'email' => $email,
				'customFields' => array(
					"street_address" => $street_address,
					'pm_name' => $pm_name,
					'is_venmo' => $is_venmo,
					'housemates' => $housemateString
				),
				'creditCard' => array(
					'number' => $number,
					'expirationMonth' => $expirationMonth,
					'expirationYear' => $expirationYear,
					'cvv' => $cvv
				)
			));

			$this->InviteHousemates($first_name, $last_name, $street_address, $pm_name, $housemates);

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

    public function InviteHousemates($first_name, $last_name, $street_address, $pm_name, $housemates)
    {
			foreach ($housemates as $housemate){
					if (!array_key_exists('email', $housemate))
						continue;

					$subject = 'Your rent is due! Join me in paying online with Cribspot';
					$template = 'rentpay_invitation';
					$this->set('inviter_first_name', $first_name);
					$this->set('inviter_last_name', $last_name);
					$this->set('rentpay_url', 'localhost/Rentpays/signup?building_name='.$building_name.'&street_address='.$street_address.'&pm_name='.$pm_name);
					$this->set('rent_amount', $housemate['rent']);
          $from = $first_name.' '.$last_name.'<info@cribspot.com>';
					$to = $housemate['email'];    
					$sendAs = 'both';
					$this->SendEmail($from, $to, $subject, $template, $sendAs);
			}
    }

}
