<?php
ini_set('display_errors', 1);
class User
{
	protected $Conn;


	public function __construct($Conn)
	{
		$this->Conn = $Conn;
	}

	public function createUser($user_data)
	{
		$hashed_password = password_hash($user_data["password"], PASSWORD_DEFAULT);

		$query = "INSERT INTO users (user_email, user_pass, user_firstname, user_lastname, address1, address2, city, postcode, country) VALUES (:user_email, :user_pass, :user_firstname, :user_lastname, :address1, :address2, :city, :postcode, :country)";
		$stmt = $this->Conn->prepare($query);
		
		return $stmt->execute([
			"user_email" => $user_data["email"],
			"user_firstname" => $user_data["firstname"],
			"user_lastname" => $user_data["lastname"],
			"address1" => $user_data["address1"],
			"address2" => $user_data["address2"],
			"city" => $user_data["city"],
			"postcode" => $user_data["postcode"],
			"country" => $user_data["country"],
			"user_pass" => $hashed_password
		]);
	}

	public function loginUser($user_data)
	{
		$query = "SELECT * FROM users WHERE user_email=:user_email";
		$stmt = $this->Conn->prepare($query);
		$stmt->execute(array("user_email" => $user_data["email"]));
		$attempt = $stmt->fetch();

		if ($attempt && password_verify($user_data["password"], $attempt["user_pass"])) {
			return $attempt;
		} else {
			return false;
		}
	}
}
