<?php 
use SendGrid\Mail\Mail;
use SendGrid\Mail\Personalization;
use SendGrid\Mail\To;
function send_email($to='',$to_name='',$sub='',$msg='',$attachment=''){
        //echo $to.'  '.$to_name. '   '.$sub.'  '.$msg. '   '; die;
	    $email = new Mail();
		$email->setFrom('sadik.jsr@gmail.com');		
		$email->setSubject($sub);
		$email->addTo($to,$to_name);	
		$email->addContent("text/html",$msg);	
		
		if($attachment != ''){
		$file_encoded    = base64_encode(file_get_contents($attachment));
		$email->addAttachment(
							   $file_encoded,
							   "application/pdf",
							   "attachment.pdf",
							   "attachment"
							);
		}
		$API_KEY = "";
		$sendgrid = new \SendGrid($API_KEY);		
		try {	
			$response = $sendgrid->send($email);
			return $response->statusCode();
		} catch (Exception $e) {
			return false;
		} 
		/*
		try {
			$response = $sendgrid->send($email);
			print $response->statusCode() . "\n";
			print_r($response->headers());
			print $response->body() . "\n";
		} catch (Exception $e) {
			echo 'Caught exception: '. $e->getMessage() ."\n";
		} 
		return "success";
	    */
}

?>