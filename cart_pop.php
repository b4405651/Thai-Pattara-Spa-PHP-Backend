<?php
include_once("includes/cart.php");
$cart = new CART();
echo $cart->pop($_POST["REMOVE_ID"]);
?>