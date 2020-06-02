<?php
/** 
 * @author Joomla! Extensions Store
 * @package INSTANTPAYPAL
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ( 'Direct Access to this location is not allowed.' );

/**
 * Class plugin
 *
 * @package INSTANTPAYPAL
 * @since 1.0
 */ 
jimport ( 'joomla.plugin.plugin' );
class plgContentInstantPaypal extends JPlugin { 
	
	public function onContentAfterDisplay($context, &$article, &$params, $page = 0) { 
		// Exclude admin exec and not authorized
		$app = JFactory::getApplication ();
		$doc = JFactory::getDocument();
		/* @var $doc JDocumentHtml */
		$docType = $doc->getType();
		
		if ($app->isAdmin () || JRequest::getCmd ( 'task' ) == 'edit' || JRequest::getCmd ( 'layout' ) == 'edit') {
			return;
		}
		$session = JFactory::getSession();
		$DBO = JFactory::getDBO();
		
		$matches = array ();
		$overrides = array ();
		$btnimg = '';
		$mode = '';
		$additionalFormHtml = null;
		if(!isset($article->text)) {
			$article->text = &$article->introtext;
		}
		
		// Check document type
		if (strcmp("html", $docType) != 0) {
			$article->text = preg_replace("/{instantpaypal}(.*?){\/instantpaypal}/i", '', $article->text);
			return;
		}
		// Output JS APP nel Document
		if(JRequest::getCmd('print')) {
			$article->text = preg_replace("/{instantpaypal}(.*?){\/instantpaypal}/i", '', $article->text);
			return;
		}
		
		preg_match_all ( '/{instantpaypal}(.*?){\/instantpaypal}/', $article->text, $matches, PREG_PATTERN_ORDER );
		if (count ( $matches [0] )) {
			for($i = 0; $i < count ( $matches [0] ); $i ++) {
				// Reset resources
				$additionalFormHtml = null;
				$mode = null;
				// Init overrides element analysis
				$overridesArray = array();
				$overrides = strlen(trim($matches [1] [$i]) )? explode ( ",", trim($matches [1] [$i] )) : array(); 
				if(count($overrides)) {
					foreach ($overrides as $overrideParam) {
						$temp = explode ( "=", $overrideParam );
						$overridesArray[$temp[0]] = $temp[1];
					}
				}
				 
				// Init overrides variables with default param fallback
				$action = $originalAction = array_key_exists('action', $overridesArray) ? $overridesArray['action'] : $this->params->get('button_type', 'pay');
				$price = array_key_exists('price', $overridesArray) ? $overridesArray['price'] : $this->params->get('default_price', 0);
				$productName = array_key_exists('productname', $overridesArray) ? $overridesArray['productname'] : $this->params->get('default_productname', 'ProductDemo');

				// TYPE DONATE
				if (strtolower ( $action ) == "donate") {
					$action = '_donations';
					$btnimg = 'https://www.paypal.com/' . $this->params->get ( 'button_path', 'en_US' ) . '/i/btn/btn_donate' . $this->params->get ( 'default_btnsize', '_SM' ) . '.gif';
				} else if (preg_match('/cart/i', $action)) { // TYPE CART 
					// Setting dell'add mode to cart
					$mode = '<input type="hidden" name="add" value="1" />';
					
				 	if (strtolower ( $action ) == "fullcart") {
						$btnimg = 'https://www.paypal.com/' . $this->params->get ( 'button_path', 'en_US' ) . '/i/btn/btn_cart' . $this->params->get ( 'default_btnsize', '_SM' ) . '.gif';
						$btnimgview = 'https://www.paypal.com/' . $this->params->get ( 'button_path', 'en_US' ) . '/i/btn/btn_viewcart' . $this->params->get ( 'default_btnsize', '_SM' ) . '.gif';
					} else if (strtolower ( $action ) == "addtocart") {
						$btnimg = 'https://www.paypal.com/' . $this->params->get ( 'button_path', 'en_US' ) . '/i/btn/btn_cart' . $this->params->get ( 'default_btnsize', '_SM' ) . '.gif';
						$btnimgview = ''; 
					} else if (strtolower ( $action ) == "showcart") {
						$btnimgview = 'https://www.paypal.com/' . $this->params->get ( 'button_path', 'en_US' ) . '/i/btn/btn_viewcart' .  $this->params->get ( 'default_btnsize', '_SM' ) . '.gif';
						$btnimg = ''; 
					}
					
					if (strtolower ( $action ) == "fullcart" || strtolower ( $action ) == "showcart") { 
						// view button
						$additionalFormHtml = 	'<form style="margin-top: 10px" class="subform ' . $this->params->get ( 'css_form_class', '' ) . '" name="instantpaypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="' . $this->params->get ( 'open_window', '_blank' ) . '"> 
													<input type="hidden" name="business" value="' . $this->params->get ( 'paypal_email', '' ) . '" />  
													<input type="hidden" name="cmd" value="_cart" /> 
													<input type="hidden" name="display" value="1" />
													<input type="hidden" name="lc" value="' . $this->params->get ( 'country_code', 'US' ) . '" />
				                 					<input type="hidden" name="charset" value="utf-8" />
													<input type="image" name="submit" style="border: 0;" src="' . $btnimgview . '" alt="PayPal - The safer, easier way to pay online" /> 
												</form>'; 
					} 
					// Override cmd paypal
					$action = '_cart';
				} else if (strtolower ( $action ) == "pay") { // TYPE PAY
					$action = '_xclick';
					$btnimg = 'https://www.paypal.com/' . $this->params->get ( 'button_path', 'en_US' ) . '/i/btn/btn_paynow' . $this->params->get ( 'default_btnsize', '_SM' ) . '.gif';
				} else { // DEFAULT TYPE XCLICK
					$action = '_xclick';
					$btnimg = 'https://www.paypal.com/' . $this->params->get ( 'button_path', 'en_US' ) . '/i/btn/btn_buynow' . $this->params->get ( 'default_btnsize', '_SM' ) . '.gif';
				}
				
				$formActionPP =  $this->params->get ( 'sandbox_mode', 0) ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
				$formHtml = '<form class="' . $this->params->get ( 'css_form_class', '' ) . '" name="instantpaypal" action="' . $formActionPP . '" method="post" target="' . $this->params->get ( 'open_window', '_blank' ) . '">
							  	<input type="hidden" name="business" value="' . $this->params->get ( 'paypal_email', '' ) . '" />
							  	<input type="hidden" name="cmd" value="' . $action . '" />
					  			<input type="hidden" name="amount" value="' . $price . '" />
							  	<input type="hidden" name="item_name" value="' . $productName . '" />' .
							  	$mode .
							  	'<input type="hidden" name="currency_code" value="' . $this->params->get ( 'currency_code', 'USD' ) . '" /> 
							 	<input type="hidden" name="lc" value="' . $this->params->get ( 'country_code', 'US' ) . '" />
                				<input type="hidden" name="charset" value="utf-8" />';
				
				
				$formHtml .= '<input type="image" name="submit" style="border: 0;" src="' . $btnimg . '" alt="PayPal - The safer, easier way to pay online" />';
				
				$formHtml .= '</form>';
				
				$additionalInfo = null;
				if($this->params->get('showxtdinfo', 1)) {
					$additionalInfo = '<div class="' . $this->params->get('css_infoxtd_class', null) . '"><span>' . $productName . '</span> | <span>' . $price . ' ' . $this->params->get('currency_code', 'USD') . '</span></div>'; 
				}
				  
				// Final show forms logic
				$finalForms = strtolower ( $originalAction ) != "showcart" ? $formHtml . $additionalInfo . $additionalFormHtml  : $additionalFormHtml;
				// Replace unique per firm instance
				$instance = $matches [1] [$i];
				$article->text = $article->introtext = str_replace("{instantpaypal}$instance{/instantpaypal}", $finalForms, $article->text );
			}
		}
		return null;
	}
}