<?php

$current_user = $_COOKIE['current_user'];

$db_host = "andrewparry.uosweb.co.uk";
$db_username = "projectclimbroot";
$db_pass = "password";
$db_name = "projectclimb";

$mysqli = mysqli_connect($db_host, $db_username, $db_pass, $db_name);
if (!$mysqli) {
	die("Connection failed: " . mysqli_connect_error());
}

$get_current_user = "SELECT user_id FROM users WHERE user_email = '$current_user'";
$got_current_user = mysqli_query($mysqli, $get_current_user);

if (mysqli_num_rows($got_current_user) > 0) {
	while ($row = mysqli_fetch_assoc($got_current_user)) {
		$user_id = $row["user_id"];
	}
}

$get_order_id = "SELECT order_id FROM orders WHERE user_id = '$user_id'";
$got_order_id = mysqli_query($mysqli, $get_order_id);

if (mysqli_num_rows($got_order_id) > 0) {
	while ($row = mysqli_fetch_assoc($got_order_id)) {
		$order_id = $row["order_id"];
	}
}

$get_orders = "SELECT * FROM order_details WHERE order_id = '$order_id'";
$got_orders = mysqli_query($mysqli, $get_orders);

if (mysqli_num_rows($got_orders) > 0) {
	while ($row = mysqli_fetch_assoc($got_orders)) {
		$product_id = $row["product_id"];
		$product_name = $row["product_name"];
		$price = $row["price"];
		$quantity = $row["quantity"];
	}
}

setcookie("product_id", $product_id, time()+300);
setcookie("product_name", $product_name, time()+300); 
setcookie("price", $price, time()+300); 
setcookie("quantity", $quantity, time()+300); 

exit();
