<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// database connection will be here
// include database and object files
include_once '../database.php';
include_once '../objects/modul.php';
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

	switch ($method) {
		case 'PUT':
			$database = new Database();
			$db = $database->getConnection();

			$modul = new Modul($db);

			// get posted data
			//$data = json_decode(file_get_contents("php://input"));

			$set = isset($_POST["codi"]);
			$setget = isset($_GET["codi"]);

			if (!$data && $setget) {
				$modul->codi = $_GET["codi"];
				$modul->Nom = $_GET["Nom"];
				$modul->Abrev = $_GET["Abrev"];
			} elseif (!$data && $set) {
				$modul->codi = $_POST["codi"];
				$modul->Nom = $_POST["Nom"];
				$modul->Abrev = $_POST["Abrev"];
			} else {
				// set product property values
				$modul->codi = $data->codi;
				$modul->Nom = $data->Nom;
				$modul->Abrev = $data->Abrev;
			}
			// make sure data is not empty
			if (
				!empty($modul->codi)
			) {

				// create the modul
				if ($modul->update()) {

					// set response code - 201 created
					http_response_code(200);

					// tell the user
					echo json_encode(array("message" => "Modul was updated."));
				}

				// if unable to create the product, tell the user
				else {

					// set response code - 503 service unavailable
					http_response_code(503);

					// tell the user
					echo json_encode(array("message" => "Unable to update modul."));
				}
			}

			// tell the user data is incomplete
			else {

				// set response code - 400 bad request
				http_response_code(400);
				echo json_encode($data);
				// tell the user
				echo json_encode(array("message" => "Unable to create modul. Data is incomplete."));
			}
			break;
		case 'POST':
			$database = new Database();
			$db = $database->getConnection();

			$modul = new Modul($db);

			// get posted data
			//$data = json_decode(file_get_contents("php://input"));

			$set = isset($_POST["Nom"]);
			$setget = isset($_GET["Nom"]);

			if (!$data && $setget) {
				$modul->Nom = $_GET["Nom"];
				$modul->Abrev = $_GET["Abrev"];
			} elseif (!$data && $set) {
				$modul->Nom = $_POST["Nom"];
				$modul->Abrev = $_POST["Abrev"];
			} else {
				// set product property values
				$modul->Nom = $data->Nom;
				$modul->Abrev = $data->Abrev;
			}

			if (
				!empty($modul->Nom) &&
				!empty($modul->Abrev)
			) {

				// create the modul
				if ($modul->create()) {

					// set response code - 201 created
					http_response_code(201);

					// tell the user
					echo json_encode($modul);
				}

				// if unable to create the product, tell the user
				else {

					// set response code - 503 service unavailable
					http_response_code(503);

					// tell the user
					echo json_encode(array("message" => "Unable to create modul."));
				}
			}

			// tell the user data is incomplete
			else {

				// set response code - 400 bad request
				http_response_code(400);
				echo json_encode($data);
				// tell the user
				echo json_encode(array("message" => "Unable to create modul. Data is incomplete."));
			}
			break;
		case 'GET':
			// instantiate database and product object
			$database = new Database();
			$db = $database->getConnection();

			// initialize object
			$modul = new Modul($db);

			// read products will be here
			// query products
			$stmt = $modul->read();
			$num = $stmt->rowCount();

			// check if more than 0 record found
			if ($num > 0) {

				// products array
				$modul_arr = array();
				$modul_arr["records"] = array();

				// retrieve our table contents
				// fetch() is faster than fetchAll()
				// http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					// extract row
					// this will make $row['name'] to
					// just $name only
					extract($row);

					$modul_item = array(
						"codi" => $codi,
						"Nom" => $Nom,
						"Abrev" => $Abrev
					);

					array_push($modul_arr["records"], $modul_item);
				}

				// set response code - 200 OK
				http_response_code(200);

				// show products data in json format
				echo json_encode($modul_arr);
			}

			// no products found will be here
			break;
	}
}
