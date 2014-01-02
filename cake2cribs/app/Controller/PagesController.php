<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Pages';
	//public $components = array('Facebook.Connect');

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 */
	public function beforeFilter(){
    parent::beforeFilter();
    $this->Auth->allow('display');
    $this->Auth->allow('TermsOfUse');
    $this->Auth->allow('PrivacyPolicy');
    $this->Auth->allow('Disclaimer');
    $this->Auth->allow('NewspaperTest');
  }
  	
    /*
    TODO: exclude this action in robots.txt
    PM Admin interface to login as any property manager.
    Generates a login link for every PM
    */
    public function PMAdmin()
    { 
      /* Ensure this request is coming from localhost via ssh tunnel */
      $ip_address = $this->request->clientIp();
      if (strcmp($ip_address, '127.0.0.1'))
        throw new NotFoundException();

      /* Get login URLs for all property managers */
      App::Import('model', 'User');
      $User = new User();
      $propertyManagers = $User->find('all', array(
          'conditions' => array(
              'User.user_type' => 1,
              'LoginCode.is_permanent' => 1
          ),
          'contain' => array('LoginCode'),
          'joins' => array(
              array('table' => 'login_codes',
                  'alias' => 'LoginCode',
                  'type' => 'INNER',
                  'conditions' => array(
                      'LoginCode.user_id = User.id'
                  ),
              )   
          )
      ));
      $loginLinks = array();
      foreach ($propertyManagers as $pm){
          if (!array_key_exists('User', $pm) || !array_key_exists('LoginCode', $pm) ||
              !array_key_exists('id', $pm['User']) || !array_key_exists(0, $pm['LoginCode']) || 
              !array_key_exists('code', $pm['LoginCode'][0]))
              continue;

          $nextLink = array(
              'link' => 'https://www.cribspot.com/users/PMLogin?id='.$pm['User']['id'].'&code='.$pm['LoginCode'][0]['code'],
              'company_name' => $pm['User']['company_name'],
              'city' => $pm['User']['city'],
              'state' => $pm['User']['state']
          );
          array_push($loginLinks, $nextLink);
      }

      $this->set('loginLinks', $loginLinks);
    }

  	public function NewspaperTest()
  	{
  		
  	}

  	/*public function GetCribspotFeaturedListings()
  	{
  		CakeLog::write('featuredListings', '1');
  		$token = Configure::read('MY_API_TOKEN');
        $token = urlencode($token);
        $url = Configure::read('HTTP_TYPE')."://www.cribspot.com/FeaturedListings/newspaper?secret_token=" . $token;
        $featuredListings = file_get_contents($url);
        CakeLog::write('featuredListings', '2');
      	$this->set('featuredListings', $featuredListings);
  	}*/

  	public function TermsOfUse()
  	{

  	}

  	public function PrivacyPolicy()
  	{

  	}

  	public function Disclaimer()
  	{

  	}

  	public function login()
  	{

  	}

    public function WelcomeEmail()
    {

    }

	public function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));
	}
}
