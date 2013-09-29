<?php
	if (isset($error))
		echo json_encode($error);

	else
	{
		$count += (count($messages)-1);

		$response = array();

		foreach(array_reverse($messages) as $message){
			$message_result = array();
			$message_result['side'] = ($user_id == $message['User']['id']) ? "right" : "left" ;
			$message_result['pic'] = ($message['User']['facebook_id']) ? "https://graph.facebook.com/".$message['User']['facebook_id']."/picture?width=80&height=80" : '/img/head_medium.jpg' ;
			$message_result['id'] = $message['Message']['message_id'];
			$message_result['name'] = (intval($message['User']['user_type']) == 0) ? $message['User']['first_name'] : $message['User']['company_name'] ;
			$message_result['time_ago'] = $this->Time->timeAgoInWords($message['Message']['created'], 
				array('accuracy' => array('year'=>'year', 'month'=>'month', 'week'=>'week','day' => 'day', 'hour'=>'hour')));
			$message_result['body'] = str_replace(array("\r\n","\r","\n"),'<br>',$message['Message']['body']);
			$message_result['count'] = $count--;
			array_push($response, $message_result);
		}

		echo json_encode($response);
	}
?>