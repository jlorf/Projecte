<?php
// required headers
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header("Content-Type: application/json; charset=UTF-8");

// database connection will be here
// include database and object files
include_once '../database.php';
include_once '../objects/unitatformativa.php';
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

			$unitatformativa = new UnitatFormativa($db);

			$set = isset($_POST["codi"]);
			$setget = isset($_GET["codi"]);

			if (!$data && $setget) {
				$unitatformativa->codi = $_GET["codi"];
				$unitatformativa->Nom = $_GET["Nom"];
				$unitatformativa->Abrev = $_GET["Abrev"];
				$unitatformativa->Hores = $_GET["Hores"];
				$unitatformativa->Modul = $_GET["Modul"];
			} elseif (!$data && $set) {
				$unitatformativa->codi = $_POST["codi"];
				$unitatformativa->Nom = $_POST["Nom"];
				$unitatformativa->Abrev = $_POST["Abrev"];
				$unitatformativa->Hores = $_POST["Hores"];
				$unitatformativa->Modul = $_POST["Modul"];
			} else {
				// set product property values
				$unitatformativa->codi = $data->codi;
				$unitatformativa->Nom = $data->Nom;
				$unitatformativa->Abrev = $data->Abrev;
				$unitatformativa->Hores = $data->Hores;
				$unitatformativa->Modul = $data->Modul;
			}

			// make sure data is not empty
			if (
				!empty($unitatformativa->codi)
			) {

				// create the unitatformativa
				if ($unitatformativa->update()) {

					// set response code - 201 created
					http_response_code(200);

					// tell the user
					echo json_encode(array("message" => "UnitatFormativa was updated."));
				}

				// if unable to create the product, tell the user
				else {

					// set response code - 503 service unavailable
					http_response_code(503);

					// tell the user
					echo json_encode(array("message" => "Unable to update unitatformativa."));
				}
			}

			// tell the user data is incomplete
			else {

				// set response code - 400 bad request
				http_response_code(400);
				echo json_encode($data);
				// tell the user
				echo json_encode(array("message" => "Unable to create unitatformativa. Data is incomplete."));
			}
			break;
		case 'POST':
			$database = new Database();
			$db = $database->getConnection();

			$unitatformativa = new UnitatFormativa($db);

			$set = isset($_POST["Nom"]);
			$setget = isset($_GET["Nom"]);

			if (!$data && $setget) {
				$unitatformativa->Nom = $_GET["Nom"];
				$unitatformativa->Abrev = $_GET["Abrev"];
				$unitatformativa->Hores = $_GET["Hores"];
				$unitatformativa->Modul = $_GET["Modul"];
			} elseif (!$data && $set) {
				$unitatformativa->Nom = $_POST["Nom"];
				$unitatformativa->Abrev = $_POST["Abrev"];
				$unitatformativa->Hores = $_POST["Hores"];
				$unitatformativa->Modul = $_POST["Modul"];
			} else {
				// set product property values
				$unitatformativa->Nom = $data->Nom;
				$unitatformativa->Abrev = $data->Abrev;
				$unitatformativa->Hores = $data->Hores;
				$unitatformativa->Modul = $data->Modul;
			}

			// make sure data is not empty
			if (
				!empty($unitatformativa->Nom) &&
				!empty($unitatformativa->Abrev) &&
				!empty($unitatformativa->Hores) &&
				!empty($unitatformativa->Modul)
			) {
				if (empty($unitatformativa->Esta)) {
					$unitatformativa->Esta = 0;
				}

				// create the unitatformativa
				if ($unitatformativa->create()) {

					// set response code - 201 created
					http_response_code(201);

					// tell the user
					echo json_encode($unitatformativa);
				}

				// if unable to create the product, tell the user
				else {

					// set response code - 503 service unavailable
					http_response_code(503);

					// tell the user
					echo json_encode(array("message" => "Unable to create unitatformativa."));
				}
			}

			// tell the user data is incomplete
			else {

				// set response code - 400 bad request
				http_response_code(400);
				echo json_encode($data);
				// tell the user
				echo json_encode(array("message" => "Unable to create unitatformativa. Data is incomplete."));
			}
			break;
		case 'GET':
			// instantiate database and product object
			$database = new Database();
			$db = $database->getConnection();

			// initialize object
			$unitatformativa = new UnitatFormativa($db);

			// read products will be here
			// query products
			$stmt = $unitatformativa->read();
			$num = $stmt->rowCount();

			// check if more than 0 record found
			if ($num > 0) {

				// products array
				$unitatformativa_arr = array();
				$unitatformativa_arr["records"] = array();

				// retrieve our table contents
				// fetch() is faster than fetchAll()
				// http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					// extract row
					// this will make $row['name'] to
					// just $name only
					extract($row);

					$unitatformativa_item = array(
						"codi" => $codi,
						"Nom" => $Nom,
						"Abrev" => $Abrev,
						"Hores" => $Hores,
						"Modul" => $Modul
					);

					array_push($unitatformativa_arr["records"], $unitatformativa_item);
				}

				// set response code - 200 OK
				http_response_code(200);

				// show products data in json format
				echo json_encode($unitatformativa_arr);
			}

			// no products found will be here
			break;
	}
}
