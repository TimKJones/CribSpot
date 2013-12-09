<?php

class Friendship extends AppModel {
  public $useTable = 'users_friends';
  public $actAs = array('Containable');
  public $recursive = -1;
  public $belongsTo = array(
    'User' => array(
      'className' => 'User',
      'foreign_key' => 'user_id'
      ),
    'Friend' => array(
      'className' => 'User',
      'foreign_key' => 'friend_id'
      ),
    );

  public function getHotlist($user_id) {
    // return $this->find('all', array(
    //   'contain' => array('Friend'),
    //   'conditions' => array('user_id' => $user_id, 'hotlist' => '1'),
    // ));

    return $this->find('list', array(
      'joins' => array(
        'table' => 'users',
        'alias' => 'Friend',
        'conditions' => array('Friend.id = FriendWith.friend_id')),
      'fields' => array(
        'Friend.id',
        'Friend.first_name',
        'Friend.last_name'),
      'conditions' => array(
        'FriendWith.user_id' => $user_id,
        'FriendWith.hotlist' => '1')
      ));
  }
}