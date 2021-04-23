<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here
// include database and object files
include_once '../database.php';
include_once '../objects/assistencia.php';
  
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
