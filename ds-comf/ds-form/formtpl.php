<?php
if($_POST['formid'] && file_exists('forms/'.$_POST['formid'].'.php')) {
	include 'forms/'.$_POST['formid'].'.php';
	header('Content-Type: text/html; charset='.CHARSET);
	include 'lib/class.form.php';
	$tplForm = new ClassForm;
	$outForm = "";
	$strForm = "";
	if($_POST['dsconfig']) {
		$tplForm->dsconfig = json_decode($_POST['dsconfig'],true);
	}
	if(defined('VALIDATE_HTML5') && VALIDATE_HTML5 != 1) {
		$novalidate = 'novalidate';
	} else $novalidate = '';
	$outForm = "\n";
	$outForm.= '<form id="form-'.$_POST['formid'].'" method="POST" enctype="multipart/form-data" '.$novalidate.'>';
	foreach ($zForma as $index => $field) {
		if(isset($field['class'])) {
			$fieldclass = ' '.$field['class'];
		} else $fieldclass = '';
		if(isset($field['id'])) {
			$fieldid = 'id = "'.$field['id'].'" ';
		} else $fieldid = '';
		$strForm.= "\n".'<div '.$fieldid.'class="field-'.$index.$fieldclass.'">'."\n";
		if(!isset($field['label'])) {
			$field['label'] = '';
		} else {
			$field['label'] = str_replace('(*)', '<span class="required">*</span>', $field['label']);
		}
		if(!isset($field['attributs'])) $field['attributs'] = array();
		switch ($field['type']) {
					case 'input':
						$strForm.= $tplForm -> input($field['label'],$field['attributs']);
					break;
					case 'textarea':
						$strForm.= $tplForm -> textarea($field['label'],$field['attributs']);
					break;
					case 'select':
						$strForm.= $tplForm -> select($field['label'],$field['attributs'],$field['options']);
						break;
					case 'freearea':
						$strForm.= $field['value']."\n";
					break;
				}	
		$strForm.= '</div>';
	}
	$outForm.=$strForm;
	$outForm.="\n".'</form>';
	echo($outForm);
} else {
	echo "error #".$_POST['formid'];
}
?>