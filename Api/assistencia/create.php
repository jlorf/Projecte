<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../database.php';
  
// instantiate assistencia object
include_once '../objects/assistencia.php';
  
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
   if (!empty($data->Esta)){
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
?>
