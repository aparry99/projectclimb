<?php
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);
include 'db.php';

function console_log($output, $with_script_tags = true)
{
	$js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
		');';
	if ($with_script_tags) {
		$js_code = '<script>' . $js_code . '</script>';
	}
	echo $js_code;
}

$cartData = json_decode($_COOKIE['order_details'], true);
$userData = json_decode($_COOKIE['orders'], true);
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

$query = "INSERT INTO orders (user_id, total, order_date, timestamp) VALUES ($user_id, :total, :order_date, :timestamp)";
$stmt = $Conn->prepare($query);

$stmt->execute([
	"total" => $userData['total'],
	"order_date" => $userData['order_date'],
	"timestamp" => $userData['timestamp']
]);

$sql = "SELECT order_id FROM orders ORDER BY order_id DESC LIMIT 1";
$result = mysqli_query($mysqli, $sql);

if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {
		$order_id = $row["order_id"];
	}
}

for ($i = 0; $i < count($cartData); $i++) {
	console_log($i);
	$query = "INSERT INTO order_details (order_id, product_id, product_name, price, quantity) VALUES (:order_id, :product_id, :product_name, :price, :quantity)";
	$stmt = $Conn->prepare($query);

	$stmt->execute([
		"order_id" => $order_id,
		"product_id" => $cartData[$i]['id'],
		"product_name" => $cartData[$i]['name'],
		"price" => $cartData[$i]['price'],
		"quantity" => $cartData[$i]['quantity']
	]);
}
