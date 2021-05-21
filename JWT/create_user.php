<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// files needed to connect to database
include_once '../Api/database.php';
include_once 'objects/user.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

$set = isset($_POST["firstname"]);
$setget = isset($_GET["firstname"]);

if (!$data && $setget){
  $user->firstname = $_GET["firstname"];
  $user->lastname = $_GET["lastname"];
  $user->email = $_GET["email"];
  $user->password = $_GET["password"];
} elseif (!$data && $set) {
  $user->firstname = $_POST["firstname"];
  $user->lastname = $_POST["lastname"];
  $user->email = $_POST["email"];
  $user->password = $_POST["password"];
} else {
  // set product property values
  $user->firstname = $data->firstname;
  $user->lastname = $data->lastname;
  $user->email = $data->email;
  $user->password = $data->password;
}

// create the user
if($user->create()){

    // set response code
    http_response_code(200);

    // display message: user was created
    echo json_encode(array("message" => "User was created."));
}

// message if unable to create user
else{

    // set response code
    http_response_code(400);

    // display message: unable to create user
    echo json_encode(array("message" => "Unable to create user."));
}
?>
