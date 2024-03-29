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
			$full_name = $params['full_name'];
			$email = $params['email'];
			$pm_name = $params['property_manager'];
			$street_address = $params['address'];

			/* Payment information */
			$amount = $params['amount'];
			/* Remove dollar sign if present */
			if ($amount && $amount[0] == '$')
				$amount = substr($amount, 1);

			$number = $params['number'];
			$cvv = $params['cvv'];
			$is_venmo = $params['venmo'];
			$expirationMonth = $params['month'];
			$expirationYear = $params['year'];

			$build_credit = $params['build_credit'];

			$housemates = $params['housemates'];
			$housemateString = '';
			foreach ($housemates as $housemate){
				$housemateString .= $housemate['email'].'-';
				if (array_key_exists('rent', $housemate))
					$housemateString .= "$".$housemate['rent'];
				else
					$housemateString .= '$NA';

				$housemateString .= ', ';
			}

			$customer = array(
				'firstName' => $full_name,
				'email' => $email,
				'customFields' => array(
					'build_credit' => $build_credit,
					'rent_amount' => $amount,
					'street_address' => $street_address,
					'pm_name' => $pm_name,
					'is_venmo' => $is_venmo,
					'housemates' => $housemateString
				)
			);

			if (!strcmp($is_venmo, 'no'))
				$customer['creditCard'] = array(
					'number' => $number,
					'expirationMonth' => $expirationMonth,
					'expirationYear' => $expirationYear,
					'cvv' => $cvv
				);

			CakeLog::write('bt_customer', print_r($customer, true));
			$result = Braintree_Customer::create($customer);

			if ($result->success) {
				$this->InviteHousemates($full_name, $street_address, $pm_name, $housemates);
				CakeLog::write('braintree',print_r($result, true));
			} else if ($result->transaction) {
				CakeLog::write('braintree',"Error processing transaction:");
				CakeLog::write('braintree',"\n  message: " . $result->message);
				CakeLog::write('braintree',"\n  code: " . $result->transaction->processorResponseCode);
				CakeLog::write('braintree',"\n  text: " . $result->transaction->processorResponseText);
			} else {
				CakeLog::write('braintree',"Message: " . $result->message);
				CakeLog::write('braintree',"\nValidation errors: \n");
				CakeLog::write('braintree',print_r($result->errors->deepAll(), true));
			}
		}

    public function InviteHousemates($full_name, $street_address, $pm_name, $housemates)
    {
			foreach ($housemates as $housemate){
					if (!array_key_exists('email', $housemate))
						continue;

					$subject = 'Your rent is due! Join me in paying online with Cribspot';
					$template = 'rentpay_invitation';
					$this->set('inviter_full_name', $full_name);
					$this->set('rentpay_url', 'https://www.cribspot.com/rentpay?street_address='.$street_address.'&pm_name='.$pm_name.'&rent='.$housemate['rent']);
					$this->set('rent_amount', $housemate['rent']);
          $from = $full_name.'<info@cribspot.com>';
					$to = $housemate['email'];    
					$sendAs = 'both';
					$this->SendEmail($from, $to, $subject, $template, $sendAs);
			}
    }

}
