<?php
include_once("includes/myPaypal.php");

$txt = $_POST["return_param"];
$txt = substr($txt, strpos($txt, 'lang'));
$txt = substr($txt, 0, strpos($txt, '!'));
$txt = explode('#', $txt);
$lang = $txt[1];

if($lang == '' || $lang == 'en') echo "Please Wait ...";
if($lang == 'ru') echo "Пожалуйста, подождите ...";

$pp = new myPaypal(array("returnurl" => $_SERVER["HTTP_REFERER"] . "?finish=true" . $_POST["return_param"], "cancelurl" => $_SERVER["HTTP_REFERER"]));
//echo "INIT myPaypal<BR>";
$pp->currency = "RUB";
$tmpItem = explode('!', $_POST["itemStr"]);
$items = array("name" => $tmpItem[0], "price" => $tmpItem[1], "quantity" => 1, "shipping" => 0);

$pp->addSimpleItem($items);
//echo $pp->getCartContentAsHtml();
echo $pp->getCheckoutForm();
?>