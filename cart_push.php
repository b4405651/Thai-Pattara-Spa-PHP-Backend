<?php
include_once("includes/cart.php");
$cart = new CART();
echo $cart->push($_POST["NAME"], $_POST["PRICE"]);
?>