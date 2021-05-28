<?php
class GrupClasse
{

	// database connection and table name
	public $conn;
	public $UF;
	public $Persona;
	public $professor;

	// constructor with $db as database connection
	public function __construct($db)
	{
		$this->conn = $db;
	}
	// read products
	function read()
	{
		// select all query
		$query = "SELECT * FROM `GrupClasse`";
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// execute query
		$stmt->execute();

		return $stmt;
	}

	function readMulti($ids)
	{
		// select all query
		$integerIDs = array_map('intval', $ids);
		$inQuery = implode(',', array_fill(0, count($integerIDs), '?'));
		$query = "SELECT * FROM `GrupClasse` WHERE UF IN(" . $inQuery . ")";
		// prepare query statement
		$stmt = $this->conn->prepare($query);
		foreach ($integerIDs as $k => $id) {
			$stmt->bindValue(($k + 1), $id, PDO::PARAM_INT);
		}
		// execute query
		if (!$stmt->execute())
			print_r($stmt->errorInfo());
		return $stmt;
	}

	function deleteMulti($uf, $ids, $prof)
	{
		$integerIDs = array_map('intval', $ids);
		$inQuery = implode(',', array_fill(0, count($integerIDs), '?'));
		$query = "DELETE FROM `GrupClasse` WHERE UF = :UF AND professor = :prof AND Persona NOT IN(" . $inQuery . ")";
		// prepare query statement
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(":UF", $uf);
		$stmt->bindParam(":prof", $prof);
		foreach ($integerIDs as $k => $id) {
			$stmt->bindValue(($k + 1), $id, PDO::PARAM_INT);
		}
		// execute query
		if (!$stmt->execute())
			//print_r($stmt->errorInfo());
			return $stmt;
	}

	// create product
	function create()
	{

		// query to insert record
		$query = "INSERT INTO `GrupClasse` SET UF=:UF, Persona=:Persona, professor=:professor";

		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->UF = htmlspecialchars(strip_tags($this->UF));
		$this->Persona = htmlspecialchars(strip_tags($this->Persona));
		$this->professor = htmlspecialchars(strip_tags($this->professor));

		// bind values
		$stmt->bindParam(":UF", $this->UF);
		$stmt->bindParam(":Persona", $this->Persona);
		$stmt->bindParam(":professor", $this->professor);

		// execute query
		if ($stmt->execute()) {
			return true;
		} else {
			//print_r($stmt->errorInfo());
		}

		return false;
	}
	// update product
	function update()
	{

		// query to insert record
		$query = "UPDATE `GrupClasse` SET professor=:professor WHERE UF=:UF AND Persona=:Persona";

		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->UF = htmlspecialchars(strip_tags($this->UF));
		$this->Persona = htmlspecialchars(strip_tags($this->Persona));
		$this->professor = htmlspecialchars(strip_tags($this->professor));

		// bind values
		$stmt->bindParam(":UF", $this->UF);
		$stmt->bindParam(":Persona", $this->Persona);
		$stmt->bindParam(":professor", $this->professor);

		// execute query
		if ($stmt->execute()) {
			return true;
		} else {
			//print_r($stmt->errorInfo());
		}

		return false;
	}
}
