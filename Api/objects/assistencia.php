<?php
class Assistencia{
  
	// database connection and table name
	public $conn;
	public $Alumne;
	public $UF;
	public $DataHora;
	public $Esta;

	// constructor with $db as database connection
	public function __construct($db){
		$this->conn = $db;
	}
	// read products
	function read(){
	// select all query
	    $query = "SELECT * FROM `Assistencia`";
	    // prepare query statement
	    $stmt = $this->conn->prepare($query);
	  
	    // execute query
	    $stmt->execute();
	  
	    return $stmt;
	}

	function readMulti()
	{
		$query = "SELECT * FROM `Assistencia` WHERE UF = :UF AND DataHora = :DataHora";
		// prepare query statement
		$stmt = $this->conn->prepare($query);
		
	    $stmt->bindParam(":UF", $this->UF);
	    $stmt->bindParam(":DataHora", $this->DataHora);

		// execute query
		if (!$stmt->execute())
			print_r($stmt->errorInfo());
		return $stmt;
	}

	// create product
	function create(){
	  
	    // query to insert record
	    $query = "INSERT INTO `Assistencia` SET Alumne=:Alumne, UF=:UF, DataHora=:DataHora, Present=:Esta";
	  
	    // prepare query
	    $stmt = $this->conn->prepare($query);
	  
	    // sanitize
	    $this->Alumne=htmlspecialchars(strip_tags($this->Alumne));
	    $this->UF=htmlspecialchars(strip_tags($this->UF));
	    $this->DataHora=htmlspecialchars(strip_tags($this->DataHora));
	    $this->Esta=htmlspecialchars(strip_tags($this->Esta));
	  
	    // bind values
	    $stmt->bindParam(":Alumne", $this->Alumne);
	    $stmt->bindParam(":UF", $this->UF);
	    $stmt->bindParam(":DataHora", $this->DataHora);
	    $stmt->bindParam(":Esta", $this->Esta);
	  
	    // execute query
	    if($stmt->execute()){
		return true;
	    } else {
		//print_r($stmt->errorInfo());
            }
	  
	    return false;
	      
	}
	// update product
	function update(){
	  
	    // query to insert record
	    $query = "UPDATE `Assistencia` SET Present=:Esta WHERE Alumne=:Alumne AND UF=:UF AND DataHora=:DataHora";
	  
	    // prepare query
	    $stmt = $this->conn->prepare($query);
	  
	    // sanitize
	    $this->Alumne=htmlspecialchars(strip_tags($this->Alumne));
	    $this->UF=htmlspecialchars(strip_tags($this->UF));
	    $this->DataHora=htmlspecialchars(strip_tags($this->DataHora));
	    $this->Esta=htmlspecialchars(strip_tags($this->Esta));
	  
	    // bind values
	    $stmt->bindParam(":Alumne", $this->Alumne);
	    $stmt->bindParam(":UF", $this->UF);
	    $stmt->bindParam(":DataHora", $this->DataHora);
	    $stmt->bindParam(":Esta", $this->Esta);
	  
	    // execute query
	    if($stmt->execute()){
		return true;
	    } else {
		//print_r($stmt->errorInfo());
            }
	  
	    return false;
	      
	}
}
?>
