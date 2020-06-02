<?php
include_once("includes/cart.php");
$cart = new CART();
echo $cart->set_currency($_POST['currency']);
?>