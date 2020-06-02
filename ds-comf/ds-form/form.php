<?php
header('Content-type: application/json');
if($_POST['formid'] && file_exists('forms/'.$_POST['formid'].'.php')) {
	include 'forms/'.$_POST['formid'].'.php';
	include 'lib/class.form.php';
	$cForm = new PHPMailer;
	$cForm = new ClassForm;


	function cleanpost($post) {
		$post = strip_tags(htmlspecialchars(trim($post)));
	    return $post;
	}
	array_filter($_POST,'cleanpost');

	foreach ($zForma as $field) {
		if(isset($field['attributs']['required'])) {
			if(!isset($field['attributs']['pattern'])) $field['attributs']['pattern'] = '';
			if(!isset($field['error'])) $field['error'] = '';
			$cForm->validate($field['attributs']['pattern'], $_POST[$field['attributs']['name']],$field['attributs']['name'],$field['error']);
		}
	}
	if ($cForm->error) {
		$cForm->error['error'] = 1;
		$cForm->error['formid'] = $_POST['formid'];
		echo(json_encode($cForm->error));
		exit();
	}
	$message = array();
	foreach ($zForma as $field) {
		if(isset($field['formail']) && $field['formail'] === 1) {

				$field['attributs']['name'] = preg_replace('|\[[^\]]*\]|siU', '', $field['attributs']['name']);

				if(isset($field['name_mail']) && !empty($field['name_mail'])) {
					$message[$cForm->im]['name'] = $field['name_mail'];

				} elseif(isset($field['label']) && !empty($field['label'])) {
					$field['label'] = str_replace('(*)', '', $field['label']);
					$message[$cForm->im]['name'] = trim($field['label']);
				} else $message[$cForm->im]['name'] = '';

				switch ($field['type']) {
					case 'input':
						 $message = $cForm->inputmodel($field['attributs']['type'],$field['attributs']['name'],$message);
					break;
					case 'textarea':
						 $message = $cForm->textareamodel($field['attributs']['name'],$message);
					break;
					case 'freearea':
						$message[$cForm->im]['message'] = $field['type'];
					break;
					case 'select':
						$message = $cForm->selectmodel($field['attributs']['name'], $message);
					break;
				}
		}
		$cForm->im++;
	}
	$cFormtpl = $cForm->mailtpl($message);
	$cForm->CharSet = CHARSET;
	$cForm->From = FROM_EMAIL;
	$cForm->FromName = FROM_NAME;
	foreach ($to_email as $email) {
		$cForm->addAddress($email);
	}
	foreach ($cc_email as $email) {
		$cForm->addCC($email);
	}
	$cForm->isHTML(true);
	$cForm->Subject = SUBJECT;
	$cForm->Body = $cFormtpl;
	$cForm->XMailer = $_SERVER['HTTP_HOST'];

	if(@$cForm->send()){
		echo(json_encode(array('error'=>0, 'formid' => $_POST['formid'], 'error_text'=>$good_mail)));
	} else {
		echo(json_encode(array('error'=>2, 'formid' => $_POST['formid'], 'error_text'=>$bad_mail)));
	}

	if(defined('BACK_MAIL') && BACK_MAIL == 1 && !empty($_POST['email'])) {
		$cForm->clearAddresses();
		$cForm->Body = $repeat_mail;
		$cForm->addAddress($_POST['email']);
		@$cForm->send();
	}
}
?>