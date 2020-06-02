<?php
require 'includes/PHPMailer/PHPMailerAutoload.php';

function initMail(&$mail){
	$mail->Timeout = 15;
	$mail->isSMTP();
	$mail->CharSet = 'UTF-8';
	$mail->SMTPAuth = true;
	/*$mail->Host = 'smtp.gmail.com';
	$mail->Username = 'pratoom@naturalparkresort.com';
	$mail->Password = '0819827696';
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;*/
	$mail->Host = 'smtp.yandex.com';
	$mail->Username = 'info@thaipattaraspa.com';
	$mail->Password = 'abc1234567890';
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	
	$mail->From = 'info@thaipattaraspa.com';
	//$mail->addAddress('cloud_live@windowslive.com'); 
}

if($_POST)
	$lang = $_POST["lang"];
{	
	//$to_email       = "cloud_live@windowslive.com"; //Recipient email, Replace with own email here
    
    //check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        
        $output = json_encode(array( //create JSON data
            'type'=>'error', 
            'text' => ($lang == 'ru' ? 'ошибка' : 'Request Error'),
			'targetObj' => ''
        ));
        die($output); //exit script outputting json data
    } 
    
    //Sanitize input data using PHP filter_var().
	
	
	
	$card_type = filter_var($_POST["card_type"], FILTER_SANITIZE_STRING);
	$original_price = filter_var($_POST["original_price"], FILTER_SANITIZE_STRING);
	$price = filter_var($_POST["price"], FILTER_SANITIZE_STRING);
	$currency = filter_var($_POST["currency"], FILTER_SANITIZE_STRING);
	$currency_text = filter_var($_POST["currency_text"], FILTER_SANITIZE_STRING);
	$customer_name = filter_var($_POST["customer_name"], FILTER_SANITIZE_STRING);
	$customer_email = filter_var($_POST["customer_email"], FILTER_SANITIZE_STRING);
	$customer_phone = filter_var($_POST["customer_phone"], FILTER_SANITIZE_STRING);
	$mail_subject = filter_var($_POST["mail_subject"], FILTER_SANITIZE_STRING);
	
	$date = date_create(date('Y') . '-' . date('m') . '-' . date('d'));
	$date = date_add($date, date_interval_create_from_date_string('3 months'));
	$exp_date = date_format($date, 'j F Y');
	
	$code = "MC#" . date("Y") . date("m") . date("d") . date("H") . date("i") . rand(1, 9999);
	
	// ==================== SEND TO CUSTOMER ====================
    $mail = new PHPMailer;
	initMail($mail);
	
	$mail->FromName = "THAI PATTARA CENTER";
	$mail->addAddress($customer_email);     // Add a recipient
	$mail->addReplyTo("info@thaipattaraspa.com");
	
	if($lang == 'ru'){
		//email body ru
		$message_body = "Дорогой/ая " . $customer_name . "\r\n\r\n";
		$message_body .= "Вы только что  приобрели " . $card_type . " членскую карту " . date('d') . "/" . date('m') . "/" . date('Y') . " " . date('H') . ":" . date('i') . ":" . date('s') . "\r\n\r\n";
		$message_body .= "Пожалуйста, используйте следующий код, чтобы получить вашу карту в Тай Паттара Спа.\r\n\r\n";
		$message_body .= $code . "\r\n\r\n";
		$message_body .= "С наилучшими пожеланиями,\r\n\r\n";
		$message_body .= "Тай Паттара Центр\r\n";
		$message_body .= "Тел : +7 (495) 945-88-99, +7 (985) 280-96-79, +7 (919) 777-34-95, +7 (910) 446-75-93\r\n";
		$message_body .= "E-mail : info@thaipattaraspa.com";
		
		$mail->Subject = "Ваша Карта Клиента";
	} else {
		//email body en
		$message_body = "Dear, " . $customer_name . "\r\n\r\n";
		$message_body .= "You just bought a " . $card_type . " member card on " . date('d') . "/" . date('m') . "/" . date('Y') . " " . date('H') . ":" . date('i') . ":" . date('s') . "\r\n\r\n";
		$message_body .= "Please use the following code to claim for the card at Thai Pattara Spa.\r\n\r\n";
		$message_body .= $code . "\r\n\r\n";
		$message_body .= "With Best Regards,\r\n\r\n";
		$message_body .= "Thai Pattara Center\r\n";
		$message_body .= "Tel : +7 (495) 945-88-99, +7 (985) 280-96-79, +7 (919) 777-34-95, +7 (910) 446-75-93\r\n";
		$message_body .= "E-mail : info@thaipattaraspa.com";
		
		$mail->Subject = "Your Member Card Order from Thai Pattara Center";
	}
    
	//if(isset($_POST['payment_type']) && $_POST['payment_type'] != "") $mail->Subject .= "  *** " . $_POST['payment_type'] . " ***";
	//$mail->Body = str_replace("\r\n", "<br />", $message_body);
	$mail->Body = $message_body;
	
	$mail->Send();
	//$mail->SmtpClose();
	if ($mail->IsError()) {
		//If mail couldn't be sent output error. Check your PHP email configuration (if it ever happens)
        $output = json_encode(array('type'=>'error', 'text' => ($lang == 'ru' ? 'ошибка : ' : 'Request failed : ') . $mail->ErrorInfo, 'targetObj' => ''));
        die($output);
	}
	
	// ==================== SEND TO OURSELVES ====================
    $mail = new PHPMailer;
	initMail($mail);
	
	$mail->FromName = "THAI PATTARA WEBSITE";
	//$mail->addAddress('pratoom@naturalparkresort.com');     // Add a recipient
	//$mail->addBCC('chatawat.realintegrity@gmail.com');     // Add a BCC recipient
	$mail->addReplyTo("info@thaipattaraspa.com");
	
    //email body
	$message_body = "";
	$message_body .= "SENT FROM WEB ON : " . date('d') . "/" . date('m') . "/" . date('Y') . " " . date('H') . ":" . date('i') . ":" . date('s') . "\r\n\r\n";
	$message_body .= "===== ORDER DETAIL =====\r\n";
	$message_body .= "MEMBER CARD : " . $card_type . "\r\n";
	$message_body .= "PRICE : " . number_format($price) . " " . $currency_text . ($currency != "RUB" ? " ( " . number_format($original_price) . " Rub. )" : "") . "\r\n";
	$message_body .= "CODE TO CLAIM : " . $code . "\r\n\r\n";
	
	$message_body .= "===== CUSTOMER DETAIL =====\r\n";
	$message_body .= "CUSTOMER NAME : " . $customer_name . "\r\n";
	$message_body .= "CUSTOMER E-MAIL : " . $customer_email . "\r\n";
	$message_body .= "CUSTOMER PHONE NO. : " . $customer_phone . "\r\n\r\n";
	
	/*
	payer_first_name			Customer's first name
	payer_last_name				Customer's last name
	payer_email					Customer's primary email address. Use this email to provide any credits.
	payer_address_street		Customer's street address.
	payer_address_city			City of customer's address
	payer_address_state			State of customer's address
	payer_address_country		Country of customer's address
	payer_address_zip			Zip code of customer's address.
	payer_address_status		Whether the customer provided a confirmed address.

	item_name1					Item name as passed by you
	mc_gross					Full amount of the customer's payment
	mc_currency					this is the currency of the payment.

	payment_date				Time/Date stamp generated by PayPal, in the following format: HH:MM:SS Mmm DD, YYYY PDT
	payment_status				The status of the payment:
	payment_type				Payment type.
	txn_id						Paypal transaction id.
	*/
	
	$message_body .= "===== PAYPAL PAYMENT DETAIL =====\r\n";
	$message_body .= "PAYER FIRST NAME : " . $_POST["payer_first_name"] . "\r\n";
	$message_body .= "PAYER LAST NAME : " . $_POST["payer_last_name"] . "\r\n";
	$message_body .= "PAYER E-MAIL : " . $_POST["payer_email"] . "\r\n";
	$message_body .= "PAYER ADDRESS STREET : " . $_POST["payer_address_street"] . "\r\n";
	$message_body .= "PAYER ADDRESS CITY : " . $_POST["payer_address_city"] . "\r\n";
	$message_body .= "PAYER ADDRESS STATE : " . $_POST["payer_address_state"] . "\r\n";
	$message_body .= "PAYER ADDRESS COUNTRY : " . $_POST["payer_address_country"] . "\r\n";
	$message_body .= "PAYER ADDRESS ZIP CODE : " . $_POST["payer_address_zip"] . "\r\n";
	$message_body .= "PAYER ADDRESS STATUS : " . $_POST["payer_address_status"] . "\r\n";
	$message_body .= "\r\n";
	$message_body .= "ITEM NAME : " . $_POST["item_name1"] . "\r\n";
	$message_body .= "FULL AMOUNT OF PAYMENT : " . $_POST["mc_gross"] . "\r\n";
	$message_body .= "PAYMENT CURRENCY : " . $_POST["mc_currency"] . "\r\n";
	$message_body .= "\r\n";
	$message_body .= "PAYMENT DATE : " . $_POST["payment_date"] . "\r\n";
	$message_body .= "PAYMENT STATUS : " . $_POST["payment_status"] . "\r\n";
	$message_body .= "PAYMENT TYPE : " . $_POST["payment_type"] . "\r\n";
	$message_body .= "PAYPAL TRANSACTION ID : " . $_POST["transaction-id"] . "\r\n";
	
	
	$mail->Subject = $mail_subject;
	//$mail->Body = str_replace("\r\n", "<br />", $message_body);
	$mail->Body = $message_body;
	
	$mail->Send();
	//$mail->SmtpClose();
	if ($mail->IsError()) {
		//If mail couldn't be sent output error. Check your PHP email configuration (if it ever happens)
        $output = json_encode(array('type'=>'error', 'text' => ($lang == 'ru' ? 'ошибка : ' : 'Request failed : ') . $mail->ErrorInfo, 'targetObj' => ''));
        die($output);
	} else {
		$output = json_encode(array('type'=>'success'));
        die($output);
	}
}
?>