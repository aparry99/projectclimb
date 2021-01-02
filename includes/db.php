<?php
define("DB_HOST", "andrewparry.uosweb.co.uk");
define("DB_NAME", "projectclimb");
define("DB_USER", "projectclimbroot");
//simple password just for testing, in a real use case would choose a more secure password
define("DB_PASS", "password");

try {
	$Conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
	$Conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$Conn->setAttribute(PDO::ATTR_PERSISTENT, true);
	$Conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo $e->getMessage();
	exit();
}
