<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here
// include database and object files
include_once '../database.php';
include_once '../objects/assistencia.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'PUT':
	$database = new Database();
	$db = $database->getConnection();
	  
	$assistencia = new Assistencia($db);
	  
	// get posted data
	$data = json_decode(file_get_contents("php://input"));
	  
	// make sure data is not empty
	if(
	    !empty($data->Alumne) &&
	    !empty($data->UF) &&
	    !empty($data->DataHora)
	){
	   if (empty($data->Esta)){
		$data->Esta = 0;
	   }
	  
	    // set assistencia property values
	    $assistencia->Alumne = $data->Alumne;
	    $assistencia->UF = $data->UF;
	    $assistencia->DataHora = $data->DataHora;
	    $assistencia->Esta = $data->Esta;
	  
	    // create the assistencia
	    if($assistencia->update()){
	  
		// set response code - 201 created
		http_response_code(200);
	  
		// tell the user
		echo json_encode(array("message" => "Assistencia was updated."));
	    }
	  
	    // if unable to create the product, tell the user
	    else{
	  
		// set response code - 503 service unavailable
		http_response_code(503);
	  
		// tell the user
		echo json_encode(array("message" => "Unable to update assistencia."));
	    }
	}
	  
	// tell the user data is incomplete
	else{
	  
	    // set response code - 400 bad request
	    http_response_code(400);
	    echo json_encode($data);
	    // tell the user
	    echo json_encode(array("message" => "Unable to create assistencia. Data is incomplete."));
	}
        break;
    case 'POST':
	$database = new Database();
	$db = $database->getConnection();
	  
	$assistencia = new Assistencia($db);
	  
	// get posted data
	$data = json_decode(file_get_contents("php://input"));
	  
	// make sure data is not empty
	if(
	    !empty($data->Alumne) &&
	    !empty($data->UF) &&
	    !empty($data->DataHora)
	){
	   if (empty($data->Esta)){
		$data->Esta = 0;
	   }
	  
	    // set assistencia property values
	    $assistencia->Alumne = $data->Alumne;
	    $assistencia->UF = $data->UF;
	    $assistencia->DataHora = $data->DataHora;
	    $assistencia->Esta = $data->Esta;
	  
	    // create the assistencia
	    if($assistencia->create()){
	  
		// set response code - 201 created
		http_response_code(201);
	  
		// tell the user
		echo json_encode(array("message" => "Assistencia was created."));
	    }
	  
	    // if unable to create the product, tell the user
	    else{
	  
		// set response code - 503 service unavailable
		http_response_code(503);
	  
		// tell the user
		echo json_encode(array("message" => "Unable to create assistencia."));
	    }
	}
	  
	// tell the user data is incomplete
	else{
	  
	    // set response code - 400 bad request
	    http_response_code(400);
	    echo json_encode($data);
	    // tell the user
	    echo json_encode(array("message" => "Unable to create assistencia. Data is incomplete."));
	}
        break;
    case 'GET':
	// instantiate database and product object
	$database = new Database();
	$db = $database->getConnection();
	  
	// initialize object
	$assistencia = new Assistencia($db);
	  
	// read products will be here
	// query products
	$stmt = $assistencia->read();
	$num = $stmt->rowCount();
	  
	// check if more than 0 record found
	if($num>0){
	  
	    // products array
	    $assistencia_arr=array();
	    $assistencia_arr["records"]=array();
	  
	    // retrieve our table contents
	    // fetch() is faster than fetchAll()
	    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
	    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		// extract row
		// this will make $row['name'] to
		// just $name only
		extract($row);
	  
		$assistencia_item=array(
		    "Alumne" => $Alumne,
		    "UF" => $UF,
		    "DataHora" => $DataHora,
		    "Present" => $Present
		);
	  
		array_push($assistencia_arr["records"], $assistencia_item);
	    }
	  
	    // set response code - 200 OK
	    http_response_code(200);
	  
	    // show products data in json format
	    echo json_encode($assistencia_arr);
	}
	  
	// no products found will be here
       break;
}
