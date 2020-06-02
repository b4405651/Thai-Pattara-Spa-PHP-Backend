<?php
require 'lib/class.phpmailer.php';
class ClassForm extends PHPMailer {
    public $error = array();
    public $dsconfig = array();
    public $im = 0;
    public function changevalue($attributs, $fieldtype, $options = array()) {
    	$changeresult = array();
    	$attributs['name'] = preg_replace('|\[[^\]]*\]|siU', '', $attributs['name']);
    	if($this->dsconfig[$attributs['name']]) {
    		switch ($fieldtype) {
    			case 'input':
    				$changeresult = $attributs;
    				$changeresult['value'] = $this->dsconfig[$attributs['name']];
    			break;
    			case 'textarea':
    				$changeresult = $attributs;
    				$changeresult['value'] = $this->dsconfig[$attributs['name']];
    			break;
    			case 'select':
    				$changeresult = $options;
    				$changeresult = $this->dsconfig[$attributs['name']];
    			break;
    		}
    		return $changeresult;
    	} else {
    		if($fieldtype == 'select') {
    			return $options;
    		} else return $attributs;
    	}
    }

    public function validate($pattern,$value,$name,$errorfield) {
		if ((empty($value) || (KOLSIM > 0 && mb_strlen($value, CHARSET) < KOLSIM)) || (!empty($pattern) && !preg_match('/'.$pattern.'/', $value))) {
			$this->error[$name] = 1;
		}
		if(!empty($errorfield) && !empty($this->error[$name])) {
			$this->error[$name] = $errorfield;
		}elseif(!empty($this->error[$name]) && defined('ERROR_EMPTY')) {
			$this->error[$name] = ERROR_EMPTY;
		}
	}

    public function input($label, $attributs) {
		$input = '';
		$attributs = $this->changevalue($attributs,'input');
		if(isset($label) && !empty($label)){
			if(isset($attributs['id']) && !empty($attributs['id'])) {
				$forid = ' for = "'.$attributs['id'].'"';
			} else $forid = '';
			$input='<label'.$forid.'>'.$label.'</label>'."\n";
		}
		$input.= '<input';
		foreach ($attributs as $attr => $avalue) {
			switch ($attr) {
				case 'required':
					$input.=' required';
					break;
				case 'autofocus':
					$input.=' autofocus';
					break;
				case 'checked':
					$input.=' checked';
					break;
				
				default:
					$input.= ' '.$attr.'="'.$avalue.'" ';
					break;
			}
		}
		$input.= '>'."\n";
		return $input;
	}

    public function textarea($label, $attributs) {
    	$textarea ="";
		$attributs = $this->changevalue($attributs,'textarea');
		if(isset($label) && !empty($label)){
			if(isset($attributs['id']) && !empty($attributs['id'])) {
				$forid = ' for = "'.$attributs['id'].'"';
			} else $forid = '';
			$textarea='<label'.$forid.'>'.$label.'</label>'."\n";
		}
		$textarea.= '<textarea';
		foreach ($attributs as $attr => $avalue) {
			switch ($attr) {
				case 'required':
					$textarea.=' required';
					break;
				case 'autofocus':
					$textarea.=' autofocus';
					break;
				
				default:
					if($attr!='value'){
						$textarea.= ' '.$attr.'="'.$avalue.'" ';
					}
					break;
			}
		}
		if(isset($attributs['value']) && !empty($attributs['value'])) {
			$textarea.= '>'.$attributs['value'].'</textarea>'."\n";
		} else $textarea.= '></textarea>'."\n";
		return $textarea;
	}

    public function select($label, $attributs, $options) {
		$select = '';
		$options = $this->changevalue($attributs,'select',$options);
		if(isset($label) && !empty($label)){
			if(isset($attributs['id']) && !empty($attributs['id'])) {
				$forid = ' for = "'.$attributs['id'].'"';
			} else $forid = '';
			$select='<label'.$forid.'>'.$label.'</label>'."\n";
		}
		$select.= '<select';
		foreach ($attributs as $attr => $avalue) {
			switch ($attr) {
				case 'autofocus':
					$select.=' autofocus';
					break;
				case 'required':
					$select.=' required';
					break;
				case 'multiple':
					$select.=' multiple';
					break;
				case 'disabled':
					$select.=' disabled';
					break;
				default:
					$select.= ' '.$attr.'="'.$avalue.'" ';
					break;
			}
		}
		$select.= '>'."\n";
		foreach ($options as $soption) {
			$text = $soption['text'];
			unset($soption['text']);
			$select.='<option';
			foreach ($soption as $attr => $avalue) {
				switch ($attr) {
					case 'disabled':
						$select.=' disabled';
						break;
					case 'selected':
						$select.=' selected';
						break;
					default:
						$select.= ' '.$attr.'="'.$avalue.'" ';
						break;
				}
			}
		$select.= '>'.$text.'</option>'."\n";
		}
		$select.= '</select>'."\n";
		return $select;
	}

    public function inputmodel($attrtype, $attrname, $message) {
		switch ($attrtype) {
			case 'file':
				$postfiles = $_FILES[$attrname];


				if(is_array($postfiles['error'])) {
					foreach ($postfiles['error'] as $eindex => $evalue) {
						if($postfiles['error'][$eindex] === 0) {
							$this->addAttachment($postfiles['tmp_name'][$eindex],$postfiles['name'][$eindex]);
						}
					}
				} else {
					if($postfiles['error'] === 0) {
						$this->addAttachment($postfiles['tmp_name'],$postfiles['name']);
					}
				}
			break;	
			default:
				$message = $this->textareamodel($attrname, $message);
			break;
		}
		return 	$message;
	}

	public function selectmodel($attrname, $message) {
		if(!is_array($_POST[$attrname])){
			$message[$this->im]['message'] = $_POST[$attrname];
		} else {
			foreach ($_POST[$attrname] as $postval) {
				$message[$this->im]['message'] = $postval;
				$this->im++;
			}
		}
		return 	$message;		
	}

	public function textareamodel($attrname, $message)	{
		if(!is_array($_POST[$attrname])){
			$message[$this->im]['message'] = $_POST[$attrname];
		} else {
			$message[$this->im]['message'] = $_POST[$attrname][0];
			array_shift($_POST[$attrname]);
		}
		return 	$message;
	}

    public function mailtpl($message) {
		if(defined('WIDTHTABLE') && WIDTHTABLE > 0) {
				$widthtable = 'width:'.WIDTHTABLE.'px;';
		} else $widthtable = '';
		if(defined('WIDTHNAME') && WIDTHNAME > 0) {
				$widthname = 'width:'.WIDTHNAME.'px;';
			} else $widthname = 'width:150px;';
		if(defined('WIDTHMESSAGE') && WIDTHMESSAGE > 0) {
			$widthmassage = 'width:'.WIDTHMASSAGE.'px;';
		} else $widthmassage = '';
		if(defined('ALIGNNAME') && ALIGNNAME!='') {
			$alignname = 'text-align:'.ALIGNNAME.';';
		} else $alignname = 'text-align: left;';

		if(defined('ALIGNMESSAGE') && ALIGNMESSAGE!='') {
			$alignmessage = 'text-align:'.ALIGNMESSAGE.';';
		} else {
			$alignmessage = 'text-align: left;';
		}
		if(defined('HEADTABLE') && HEADTABLE!='') {
			$headtable = HEADTABLE;
		} else {
			$headtable = '';
		}
		$text_mail = '
		<table id="table-mail" cellpadding="0" style="border: 1px solid black;'.$widthtable.'margin-left: 40px;" cellspacing="0">
		  <thead>
		  <tr><th style="padding: 5px;font-weight:bold;height:30px;padding: 3px 15px;	background-color: rgb(244, 244, 244);color: #2F3788;" colspan="2" align="center">'.$headtable.'</th></tr>
		  </thead>
		  <tbody>';
		foreach ($message as $text) {
			if(!empty($text['message'])) {
				if(empty($text['name'])) $text['name'] = '&nbsp;'; 
				if($text['message'] == 'freearea') {
					$text_mail.= '<tr><td style="padding: 5px;border-top:1px dashed;" colspan="2">'.$text['name'].'</td></tr>';
				} else {
					$text_mail.= '<tr><td style="padding: 5px;border-top:1px dashed;font-weight:bold;'.$widthname.$alignname.'border-right:1px dashed;">'.$text['name'].'</td><td style="padding: 5px;'.$widthmassage.$alignmessage.'border-top:1px dashed;">'.$text['message'].'</td></tr>';
				}
			}
		}
		$text_mail.= '
		  </tbody>
		</table>';
		return $text_mail;
	}

}

?>