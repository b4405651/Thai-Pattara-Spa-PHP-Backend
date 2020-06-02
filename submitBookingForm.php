<?php
require 'includes/PHPMailer/PHPMailerAutoload.php';

function initMail(&$mail){
	$mail->Timeout = 15;
	$mail->isSMTP();
	$mail->CharSet = 'UTF-8';
	$mail->SMTPAuth = true;
	$mail->Host = 'smtp.yandex.com';
	$mail->Username = 'info@thaipattaraspa.com';
	$mail->Password = 'abc1234567890';
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	
	$mail->From = 'info@thaipattaraspa.com';
}

if($_POST)
{	
	$lang = $_POST["lang"];	
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        
        $output = json_encode(array( //create JSON data
            'type'=>'error', 
            'text' => ($lang == 'ru' ? 'ошибка' : 'Request Error'),
			'targetObj' => ''
        ));
        die($output); //exit script outputting json data
    } 
    
    //Sanitize input data using PHP filter_var().
	
	
	
	$spa_program = filter_var($_POST["spa_program"], FILTER_SANITIZE_STRING);
	$total_price = filter_var($_POST["total_price"], FILTER_SANITIZE_STRING);
	$fullname = filter_var($_POST["fullname"], FILTER_SANITIZE_STRING);
	$phone_number = filter_var($_POST["phone_number"], FILTER_SANITIZE_STRING);
	$email = filter_var($_POST["email"], FILTER_SANITIZE_STRING);
	$datetime = filter_var($_POST["datetime"], FILTER_SANITIZE_STRING);
	$master = filter_var($_POST["master"], FILTER_SANITIZE_STRING);
	$note = filter_var($_POST["note"], FILTER_SANITIZE_STRING);
	$mail_subject = filter_var($_POST["mail_subject"], FILTER_SANITIZE_STRING);
	
	// ==================== SEND TO CUSTOMER ====================
    $mail = new PHPMailer;
	initMail($mail);
	
	$mail->FromName = "THAI PATTARA CENTER";
	$mail->addAddress($email);     // Add a recipient
	$mail->addReplyTo("info@thaipattaraspa.com");
	
	if($lang == 'ru'){
		//email body ru
		$message_body = "Дорогой/ая " . $fullname . "\r\n\r\n";
		$message_body .= "Вы только что сделали резервацию массажа на " . date('d') . "/" . date('m') . "/" . date('Y') . " " . date('H') . ":" . date('i') . ":" . date('s') . "\r\n\r\n";
		$message_body .= "Ниже ваша информация о бронировании.\r\n\r\n";
		$message_body .= "Ваше полное имя : " . $fullname . "\r\n";
		$message_body .= "Ваш номер телефона : " . $phone_number . "\r\n";
		$message_body .= "Ваш E-mail : " . $email . "\r\n\r\n";
		$message_body .= "Спа программа : \r\n";
		$message_body .= $spa_program . "\r\n";
		$message_body .= "Общая стоимость : " . $total_price . "\r\n\r\n";
		$message_body .= "Дата и время вашего посещения : " . $datetime . "\r\n";
		$message_body .= "Имя вашего Мастера : " . ($master == '' ? '-' : $master) . "\r\n";
		$message_body .= "Дополнительное примечание по вашему бронированию : " . ($note == '' ? '-' : "\r\n" . $note) . "\r\n\r\n";
		$message_body .= "Если приведенная выше информация неверна или вы хотите что-то изменить. Пожалуйста, не стесняйтесь связаться с нами.\r\n\r\n";
		$message_body .= "С наилучшими пожеланиями,\r\n\r\n";
		$message_body .= "Тай Паттара Центр\r\n";
		$message_body .= "Тел : +7 (495) 945-88-99, +7 (985) 280-96-79, +7 (919) 777-34-95, +7 (910) 446-75-93\r\n";
		$message_body .= "E-mail : info@thaipattaraspa.com";
		
		$mail->Subject = "Ваше бронирование";
	} else {	
		//email body en
		$message_body = "Dear, " . $fullname . "\r\n\r\n";
		$message_body .= "You just made a massage reservation on " . date('d') . "/" . date('m') . "/" . date('Y') . " " . date('H') . ":" . date('i') . ":" . date('s') . "\r\n\r\n";
		$message_body .= "Following is your booking detail.\r\n\r\n";
		$message_body .= "Your Full Name : " . $fullname . "\r\n";
		$message_body .= "Your Phone Number : " . $phone_number . "\r\n";
		$message_body .= "Your E-mail : " . $email . "\r\n\r\n";
		$message_body .= "Spa Program : \r\n";
		$message_body .= $spa_program . "\r\n";
		$message_body .= "Total Price : " . $total_price . "\r\n\r\n";
		$message_body .= "Date and Time you will come to massage : " . $datetime . "\r\n";
		$message_body .= "You request for Master : " . ($master == '' ? '-' : $master) . "\r\n";
		$message_body .= "Extra Note for your booking : " . ($note == '' ? '-' : "\r\n" . $note) . "\r\n\r\n";
		$message_body .= "If the above information was incorrect or you wanted to change anything. Please feel free to contact us.\r\n\r\n";
		$message_body .= "With Best Regards,\r\n\r\n";
		$message_body .= "Thai Pattara Center\r\n";
		$message_body .= "Tel : +7 (495) 945-88-99, +7 (985) 280-96-79, +7 (919) 777-34-95, +7 (910) 446-75-93\r\n";
		$message_body .= "E-mail : info@thaipattaraspa.com";
		
		$mail->Subject = "Your Massage Reservation from Thai Pattara Center";
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
	$mail->addAddress('info@thaipattaraspa.com');     // Add a recipient
	$mail->addReplyTo($email);
	
    //email body
	$message_body = "";
	$message_body .= "SENT FROM WEB ON : " . date('d') . "/" . date('m') . "/" . date('Y') . " " . date('H') . ":" . date('i') . ":" . date('s') . "\r\n\r\n";
	
	$message_body .= "===== CUSTOMER DETAIL =====\r\n";
	$message_body .= "CUSTOMER NAME : " . $fullname . "\r\n";
	$message_body .= "CUSTOMER E-MAIL : " . $email . "\r\n";
	$message_body .= "CUSTOMER PHONE NO. : " . $phone_number . "\r\n\r\n";
	
	$message_body .= "===== RESERVATION DETAIL =====\r\n";
	$message_body .= "SPA PROGRAM : \r\n" . $spa_program . "\r\n";
	$message_body .= "TOTAL PRICE : " . $total_price . "\r\n";
	$message_body .= "DATE & TIME CUSTOMER WILL COME TO MASSAGE : " . $datetime . "\r\n";
	$message_body .= "CUSTOMER REQUEST FOR MASTER : " . $master . "\r\n";
	$message_body .= "EXTRA NOTE : " . ($note == '' ? '-' : "\r\n" . $note) . "\r\n\r\n";
	
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