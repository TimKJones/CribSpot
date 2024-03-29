<?php

class NewspaperAdmin extends AppModel {
    public $name = 'NewspaperAdmin';
    
    public function getByUserId($user_id){
        return $this->find('first', array('conditions'=>array('NewspaperAdmin.user_id'=>$user_id)));
    }

    public function getBySecretToken($token){
        return $this->find('first', array('conditions'=>array('NewspaperAdmin.secret_token'=>$token)));
    }
}