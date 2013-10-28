<?php 
	App::import('Vendor', 'Twilio.Twilio');
	class TwilioComponent extends Component{
	
	public function startup(Controller $controller) 
    { 
        $this->Twilio = new Twilio();
    } 

	function sms($from, $to, $message)
	{
		return $this->Twilio->sms($from, $to, $message);
	}
}