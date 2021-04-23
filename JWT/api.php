<?php

use \Firebase\JWT\JWT;

function validarJWT()
{

    // required headers
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    // required to decode jwt
    include_once 'config/core.php';
    include_once 'libs/php-jwt-master/src/BeforeValidException.php';
    include_once 'libs/php-jwt-master/src/ExpiredException.php';
    include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
    include_once 'libs/php-jwt-master/src/JWT.php';

/// get posted data
    $data = json_decode(file_get_contents("php://input"));

// get jwt
    $jwt = isset($data->jwt) ? $data->jwt : "";

// if jwt is not empty
    if ($jwt) {

        // if decode succeed, show user details
        try {
            // decode jwt
            $decoded = JWT::decode($jwt, $key, array('HS256'));

            // set response code
            http_response_code(200);

            // show user details
            echo json_encode(array(
                "message" => "Access granted.",
                "data" => $decoded->data
            ));

        } // if decode fails, it means jwt is invalid
        catch (Exception $e) {

            // set response code
            http_response_code(401);

            // tell the user access denied  & show error message
            echo json_encode(array(
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));
        }
    } // show error message if jwt is empty
    else {

        // set response code
        http_response_code(401);

        // tell the user access denied
        echo json_encode(array("message" => "Access denied."));
    }
}

function crearUsuari(){
    // required headers
    header("Access-Control-Allow-Origin: http://localhost:8080/");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// files needed to connect to database
    include_once 'config/database.php';
    include_once 'objects/user.php';

// get database connection
    $database = new Database();
    $db = $database->getConnection();

// instantiate product object
    $user = new User($db);

// get posted data
    $data = json_decode(file_get_contents("php://input"));

// set product property values
    $user->firstname = $data->firstname;
    $user->lastname = $data->lastname;
    $user->email = $data->email;
    $user->password = $data->password;

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
}
?>