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


// $products = $_POST['products'];

// $product_id = $_POST['product_id'];
// $price = $_POST['price'];
// $quantity = $_POST['quantity'];

// echo $products;
// echo "test";

// $products = json_decode(file_get_contents('php://input'), true);
// print_r($products);
// echo $products["products"];

// $arr = array();
// $arr[0] = "Mark Reed";
// $arr[1] = "34";
// $arr[2] = "Australia";
// header("Content-Type: application/json");
// echo json_encode($arr);



// $cartData = json_decode($_POST['cartData'], true);
// $deets = json_decode($_POST['deets'], true);

$cartData = json_decode($_COOKIE['order_details'], true);
$deets = json_decode($_COOKIE['orders'], true);

$query = "INSERT INTO orders (user_id, total, order_date, timestamp) VALUES (:user_id, :total, :order_date, :timestamp)";
$stmt = $Conn->prepare($query);

$stmt->execute([
	"user_id" => $deets['user_id'],
	"total" => $deets['total'],
	"order_date" => $deets['order_date'],
	"timestamp" => $deets['timestamp']
]);

$db_host = "andrewparry.uosweb.co.uk";
$db_username = "projectclimbroot";
$db_pass = "password";
$db_name = "projectclimb";

$mysqli = mysqli_connect($db_host, $db_username, $db_pass, $db_name);
if (!$mysqli) {
	die("Connection failed: " . mysqli_connect_error());
}
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


// $result = $conn->query($sql);
// $result2 = "test";
// echo "<h2>" . $result . "</h2>";
// console_log($sql);








// echo json_encode($products["products"]);



// foreach($products as $product){
// 	echo $product."\n";
// }



// $query = "INSERT INTO users (user_email, user_pass) VALUES (:user_email, :user_pass)";
// $stmt = $this->Conn->prepare($query);

// return $stmt->execute([
// 	"user_email" => $user_data["email"],
// 	"user_pass" => $hashed_password
// ]);


// $item_data_decode = json_decode($products, true);
// $meta_array = array_combine(array_column($item_data_decode['meta_data'], 'key'), $item_data_decode['meta_data']);

// if (!empty($meta_array['First Name'])) {
//   $fName = $meta_array['First Name']['value'];
// }