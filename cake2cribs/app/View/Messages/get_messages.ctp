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

			if (array_key_exists('profile_img', $message['User']) && !empty($message['User']['profile_img']))
			{
				$message_result['pic'] = '/' . $message['User']['profile_img'];
			}
			elseif (array_key_exists('facebook_id', $message['User']) && !empty($message['User']['facebook_id']))
			{
				$message_result['pic'] = "https://graph.facebook.com/".$message['User']['facebook_id']."/picture?width=80&height=80";
			}
			else
			{
				$message_result['pic'] = '/img/head_medium.jpg';
			}

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