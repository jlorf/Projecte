<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// database connection will be here
// include database and object files
include_once '../database.php';
include_once '../objects/persona.php';
// required to decode jwt
include_once '../../JWT/config/core.php';
include_once '../../JWT/libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../JWT/libs/php-jwt-master/src/ExpiredException.php';
include_once '../../JWT/libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../JWT/libs/php-jwt-master/src/JWT.php';

use \Firebase\JWT\JWT;

$method = $_SERVER['REQUEST_METHOD'];

$data = json_decode(file_get_contents("php://input"));
$set = isset($_GET["jwt"]);
$setpost = isset($_POST["jwt"]);
$jwt = null;
if (count((array)$data) <= 0 && !$set && !$setpost) {
	$jwt = null;
} else if ($set) {
	$jwt = $_GET["jwt"];
} else if ($setpost) {
	$jwt = $_POST["jwt"];
} else {
	$jwt = $data->jwt;
}

try {
	// decode jwt
	$decoded = JWT::decode($jwt, $key, array('HS256'));
} catch (Exception $e) {
	$jwt = null;
}

//$jwt = (count((array)$data) <= 0 && empty($_GET) ? null : (empty($_GET) ? null : ($set ? $_GET["jwt"] : null)));
if (!isset($jwt)) {
	http_response_code(401);
} else {
	$method = $_SERVER['REQUEST_METHOD'];

	switch ($method) {
		case 'PUT':
			$database = new Database();
			$db = $database->getConnection();

			$persona = new Persona($db);

			// get posted data
			$data = json_decode(file_get_contents("php://input"));

			// make sure data is not empty
			if (
				!empty($data->codi) &&
				!empty($data->Nom) &&
				!empty($data->Cognoms)
			) {
				if (empty($data->professor)) {
					$data->professor = 0;
				}

				// set persona property values
				$persona->codi = $data->codi;
				$persona->Nom = $data->Nom;
				$persona->Cognoms = $data->Cognoms;
				$persona->professor = $data->professor;

				// create the persona
				if ($persona->update()) {

					// set response code - 201 created
					http_response_code(200);

					// tell the user
					echo json_encode(array("message" => "Persona was updated."));
				}

				// if unable to create the product, tell the user
				else {

					// set response code - 503 service unavailable
					http_response_code(503);

					// tell the user
					echo json_encode(array("message" => "Unable to update persona."));
				}
			}

			// tell the user data is incomplete
			else {

				// set response code - 400 bad request
				http_response_code(400);
				echo json_encode($data);
				// tell the user
				echo json_encode(array("message" => "Unable to create persona. Data is incomplete."));
			}
			break;
		case 'POST':
			$database = new Database();
			$db = $database->getConnection();

			$persona = new Persona($db);

			// get posted data
			$data = json_decode(file_get_contents("php://input"));

			// make sure data is not empty
			if (
				!empty($data->Nom) &&
				!empty($data->Cognoms)
			) {
				if (empty($data->professor)) {
					$data->professor = 0;
				}

				// set persona property values
				$persona->Nom = $data->Nom;
				$persona->Cognoms = $data->Cognoms;
				$persona->professor = $data->professor;

				// create the persona
				if ($persona->create()) {

					// set response code - 201 created
					http_response_code(201);

					// tell the user
					echo json_encode($persona);
				}

				// if unable to create the product, tell the user
				else {

					// set response code - 503 service unavailable
					http_response_code(503);

					// tell the user
					echo json_encode(array("message" => "Unable to create persona."));
				}
			}

			// tell the user data is incomplete
			else {

				// set response code - 400 bad request
				http_response_code(400);
				echo json_encode($data);
				// tell the user
				echo json_encode(array("message" => "Unable to create persona. Data is incomplete."));
			}
			break;
		case 'GET':
			// instantiate database and product object
			$database = new Database();
			$db = $database->getConnection();

			// initialize object
			$persona = new Persona($db);

			// read products will be here
			// query products
			$stmt = $persona->read();
			$num = $stmt->rowCount();

			// check if more than 0 record found
			if ($num > 0) {

				// products array
				$persona_arr = array();
				$persona_arr["records"] = array();

				// retrieve our table contents
				// fetch() is faster than fetchAll()
				// http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					// extract row
					// this will make $row['name'] to
					// just $name only
					extract($row);

					$persona_item = array(
						"codi" => $codi,
						"Nom" => $Nom,
						"Cognoms" => $Cognoms,
						"professor" => $professor
					);

					array_push($persona_arr["records"], $persona_item);
				}

				// set response code - 200 OK
				http_response_code(200);

				// show products data in json format
				echo json_encode($persona_arr);
			}

			// no products found will be here
			break;
	}
}
