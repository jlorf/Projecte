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

// instantiate user object
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
// set product property values
$emailpost = empty($_POST["email"]) ? "" : $_POST["email"];
$passpost = empty($_POST["password"]) ? "" : $_POST["password"];
$user->email = (count((array)$data) <= 0 ? $emailpost : $data->email);
$email_exists = $user->emailExists();
$pass = (count((array)$data) <= 0 ? $passpost : $data->password);

// generate json web token
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// check if email exists and if password is correct
if($email_exists && password_verify($pass, $user->password)){

    $token = array(
       "iss" => $iss,
       "aud" => $aud,
       "iat" => $iat,
       "nbf" => $nbf,
       "data" => array(
           "id" => $user->id,
           "firstname" => $user->firstname,
           "lastname" => $user->lastname,
           "email" => $user->email
       )
    );

    // set response code
    http_response_code(200);

    // generate jwt
    $jwt = JWT::encode($token, $key);
    $user->jwt = $jwt;
    $user->GuardarJWT();
    echo json_encode(
            array(
                "user" => $user->firstname . " " . $user->lastname,
                "message" => "Successful login.",
                "jwt" => $jwt
            )
        );

}

// login failed
else{

    // set response code
    http_response_code(401);

    // tell the user login failed
    echo json_encode(array("message" => "Login failed."));
}
?>
