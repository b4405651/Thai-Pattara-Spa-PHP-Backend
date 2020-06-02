<?php
require 'includes/PHPMailer/PHPMailerAutoload.php';

if($_POST)
{	
	//$to_email       = "cloud_live@windowslive.com"; //Recipient email, Replace with own email here
    
    //check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        
        $output = json_encode(array( //create JSON data
            'type'=>'error', 
            'text_en' => 'Request Error',
			'text_th' => 'ระบบผิดพลาด',
			'targetObj' => ''
        ));
        die($output); //exit script outputting json data
    } 
    
    //Sanitize input data using PHP filter_var().
    $user_name      = filter_var($_POST["user_name"], FILTER_SANITIZE_STRING);
    $user_email     = filter_var($_POST["user_email"], FILTER_SANITIZE_EMAIL);
    $country_code   = filter_var($_POST["country_code"], FILTER_SANITIZE_NUMBER_INT);
    $phone_number   = filter_var($_POST["phone_number"], FILTER_SANITIZE_NUMBER_INT);
    $subject        = filter_var($_POST["subject"], FILTER_SANITIZE_STRING);
    $message        = filter_var($_POST["msg"], FILTER_SANITIZE_STRING);
	$mail_subject	= $_POST["mail_subject"];
    
    //additional php validation
    if(strlen($user_name)<1){ // If length is less than 4 it will output JSON error.
        $output = json_encode(array('type'=>'error', 'text_en' => '"Your name" is required', 'text_ru' => 'Пожалуйста, введите ваше полное имя', 'targetObj' => 'input[name="user_name"]'));
        die($output);
    }
	if(strlen($user_email)<1){ // If length is less than 4 it will output JSON error.
        $output = json_encode(array('type'=>'error', 'text_en' => '"E-Mail" is required', 'text_th' => 'Пожалуйста, введите ваш E-MAIL', 'targetObj' => 'input[name="user_email"]'));
        die($output);
    }
    if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){ //email validation
        $output = json_encode(array('type'=>'error', 'text_en' => '"E-MAIL" is not valid', 'text_th' => 'Пожалуйста, проверьте ваш E-MAIL снова', 'targetObj' => 'input[name="user_email"]'));
        die($output);
    }
	if(strlen($phone_number)<1){ // If length is less than 4 it will output JSON error.
        $output = json_encode(array('type'=>'error', 'text_en' => '"Phone number" is required', 'text_th' => 'Пожалуйста, введите ваш номер телефона', 'targetObj' => 'input[name="phone_number"]'));
        die($output);
    }
    if(!filter_var($phone_number, FILTER_SANITIZE_NUMBER_FLOAT)){ //check for valid numbers in phone number field
        $output = json_encode(array('type'=>'error', 'text_en' => 'Enter only digits in "Phone number"', 'text_th' => 'Телефон должен быть указан цифрами', 'targetObj' => 'input[name="phone_number"]'));
        die($output);
    }
    if(strlen($subject)<1){ //check emtpy subject
        $output = json_encode(array('type'=>'error', 'text_en' => '"Subject" is required', 'text_th' => 'Пожалуйста ,введите тему сообщения', 'targetObj' => 'input[name="subject"]'));
        die($output);
    }
    if(strlen($message)<1){ //check emtpy message
        $output = json_encode(array('type'=>'error', 'text_en' => '"Description" is required', 'text_th' => 'Пожалуйста введите более подробную информацию.', 'targetObj' => 'textarea[name="msg"]'));
        die($output);
    }
	
    $mail = new PHPMailer;
	
	$mail->isSMTP();
	$mail->CharSet = 'UTF-8';
	$mail->Host = 'smtp.yandex.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'info@thaipattaraspa.com';
	$mail->Password = 'abc1234567890';
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	
	$mail->From = 'info@thaipattaraspa.com';
	$mail->FromName = 'Thai Pattara Spa Website - ' . $user_name;
	$mail->addAddress('info@thaipattaraspa.com');     // Add a recipient
	$mail->addReplyTo($user_email);
	
    //email body
	$message_body = "";
	$message_body .= "SENT FROM WEB ON : " . date('d') . "/" . date('m') . "/" . date('Y') . " " . date('H') . ":" . date('i') . ":" . date('s') . "\r\n\r\n";
	$message_body .= "CONTACT NAME : " . $user_name . "\r\n";
	$message_body .= "E-MAIL : " . $user_email . "\r\n";
	$message_body .= "PHONE NUMBER : " . $phone_number . "\r\n";
	$message_body .= "SUBJECT : " . $subject . "\r\n\r\n";
	$message_body .= "DETAIL : \r\n" . $message;
    
	$mail->Subject = "MESSAGE FROM THAI PATTARA SPA WEBSITE :: '" . $subject . "'";
	//$mail->Body = str_replace("\r\n", "<br />", $message_body);
	$mail->Body = $message_body;
	
	if (!$mail->send()) {
		//If mail couldn't be sent output error. Check your PHP email configuration (if it ever happens)
        $output = json_encode(array('type'=>'error', 'text_en' => 'Request failed : ' . $mail->ErrorInfo, 'text_th' => 'ระบบผิดพลาด : ' . $mail->ErrorInfo, 'targetObj' => ''));
        die($output);
	} else {
		$output = json_encode(array('type'=>'success'));
        die($output);
	}
}
?>