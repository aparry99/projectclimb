<?php
class Product
{
	protected $Conn;

	public function __construct($Conn)
	{
		$this->Conn = $Conn;
	}

	public function getAllProducts()
	{
		$query = "SELECT * FROM products";
		$stmt = $this->Conn->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
