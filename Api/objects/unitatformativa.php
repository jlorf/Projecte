<?php
class UnitatFormativa{
  
	// database connection and table name
	public $conn;
	public $codi;
	public $Nom;
	public $Abrev;
	public $Hores;
	public $Modul;

	// constructor with $db as database connection
	public function __construct($db){
		$this->conn = $db;
	}
	// read products
	function read(){
	// select all query
	    $query = "SELECT * FROM `UnitatFormativa`";
	    // prepare query statement
	    $stmt = $this->conn->prepare($query);
	  
	    // execute query
	    $stmt->execute();
	  
	    return $stmt;
	}

	function readMulti($ids){
	// select all query
	    $integerIDs = array_map('intval', $ids);
	    $inQuery = implode(',', array_fill(0, count($integerIDs), '?'));
	    $query = "SELECT * FROM `UnitatFormativa` WHERE codi IN(" . $inQuery . ")";
	    // prepare query statement
	    $stmt = $this->conn->prepare($query);
	    foreach($integerIDs as $k => $id)
            {
	    	$stmt->bindValue(($k+1), $id, PDO::PARAM_INT);
    	    }
	    // execute query
	    if(!$stmt->execute())
  		print_r($stmt->errorInfo());
	    return $stmt;
	}

	// create product
	function create(){
	  
	    // query to insert record
	    $query = "INSERT INTO `UnitatFormativa` SET Nom=:Nom, Abrev=:Abrev, Hores=:Hores, Modul=:Modul";
	  
	    // prepare query
	    $stmt = $this->conn->prepare($query);
	  
	    // sanitize
	    $this->Nom=htmlspecialchars(strip_tags($this->Nom));
	    $this->Abrev=htmlspecialchars(strip_tags($this->Abrev));
		$this->Hores=htmlspecialchars(strip_tags($this->Hores));
		$this->Modul=htmlspecialchars(strip_tags($this->Modul));
	  
	    // bind values
	    $stmt->bindParam(":Nom", $this->Nom);
	    $stmt->bindParam(":Abrev", $this->Abrev);
		$stmt->bindParam(":Hores", $this->Hores);
		$stmt->bindParam(":Modul", $this->Modul);
	  
	    // execute query
	    if($stmt->execute()){
		// query to insert record
		$query2 = "SELECT LAST_INSERT_ID() as codi";
		// prepare query
		$stmt2 = $this->conn->prepare($query2);
		$stmt2->execute();
		$result = $stmt2->fetch();
		$this->codi = $result['codi'];
		return true;
	    } else {
		print_r($stmt->errorInfo());
            }
	  
	    return false;
	      
	}
	// update product
	function update(){
	  
	    // query to insert record
	    $query = "UPDATE `UnitatFormativa` SET Nom=:Nom, Abrev=:Abrev, Hores=:Hores, Modul=:Modul WHERE codi=:codi";
	  
	    // prepare query
	    $stmt = $this->conn->prepare($query);
	  
	    // sanitize
	    $this->codi=htmlspecialchars(strip_tags($this->codi));
	    $this->Nom=htmlspecialchars(strip_tags($this->Nom));
	    $this->Abrev=htmlspecialchars(strip_tags($this->Abrev));
		$this->Hores=htmlspecialchars(strip_tags($this->Hores));
		$this->Modul=htmlspecialchars(strip_tags($this->Modul));
	  
	    // bind values
	    $stmt->bindParam(":codi", $this->codi);
	    $stmt->bindParam(":Nom", $this->Nom);
	    $stmt->bindParam(":Abrev", $this->Abrev);
		$stmt->bindParam(":Hores", $this->Hores);
		$stmt->bindParam(":Modul", $this->Modul);
	  
	    // execute query
	    if($stmt->execute()){
		return true;
	    } else {
		print_r($stmt->errorInfo());
            }
	  
	    return false;
	      
	}
}
?>
