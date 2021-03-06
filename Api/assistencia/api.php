<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// database connection will be here
// include database and object files
include_once '../database.php';
include_once '../objects/assistencia.php';
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

			$assistencia = new Assistencia($db);

			// get posted data
			$data = json_decode(file_get_contents("php://input"));

			// make sure data is not empty
			if (
				!empty($data->Alumne) &&
				!empty($data->UF) &&
				!empty($data->DataHora)
			) {
				if (empty($data->Esta)) {
					$data->Esta = 0;
				}

				// set assistencia property values
				$assistencia->Alumne = $data->Alumne;
				$assistencia->UF = $data->UF;
				$assistencia->DataHora = $data->DataHora;
				$assistencia->Esta = $data->Esta;

				// create the assistencia
				if ($assistencia->update()) {

					// set response code - 201 created
					http_response_code(200);

					// tell the user
					echo json_encode(array("message" => "Assistencia was updated."));
				}

				// if unable to create the product, tell the user
				else {

					// set response code - 503 service unavailable
					http_response_code(503);

					// tell the user
					echo json_encode(array("message" => "Unable to update assistencia."));
				}
			}

			// tell the user data is incomplete
			else {

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

			$set = isset($_POST["uf"]);
			$setget = isset($_GET["uf"]);
			$persones = null;
			$uf = null;
			$professor = null;
			$datahora = null;

			$data = json_decode(file_get_contents("php://input"));

			if (!$data && $setget) {
				$uf = $_GET["uf"];
				$persones = $_GET["alumnes"];
				$datahora = $_GET["data"];
			} elseif (!$data && $set) {
				$uf = $_POST["uf"];
				$persones = $_POST["alumnes"];
				$datahora = $_POST["data"];
			} else {
				// set product property values
				$uf = $data->uf;
				$persones = $data->alumnes;
				$datahora = $data->data;
			}
			//echo $datahora;
			// make sure data is not empty
			if (
				!empty($uf) &&
				!empty($persones)
			) {
				$response = 200;
				foreach ($persones as $record => $val) {
					$assistencia->UF = $uf;
					$assistencia->Alumne = $val["Alumne"];
					$assistencia->Esta = $val["Esta"];
					// echo $val["Esta"];
					$assistencia->DataHora = $datahora;

					if (!isset($assistencia->Esta)) {
						$assistencia->Esta = 0;
					}
					//echo json_encode($grupclasse);
					// create the grupclasse
					if (!$assistencia->create() && !$assistencia->update()) {
						$response = 503;
					}
				}
				echo json_encode($response);
				http_response_code($response);
			}

			// tell the user data is incomplete
			else {

				// set response code - 400 bad request
				http_response_code(400);
				echo json_encode($data);
				// tell the user
				echo json_encode(array("message" => "Unable to create grupclasse. Data is incomplete."));
			}
			break;
		case 'GET':
			// instantiate database and product object
			$database = new Database();
			$db = $database->getConnection();

			// initialize object
			$assistencia = new Assistencia($db);

			$set = isset($_POST["uf"]);
			$setget = isset($_GET["uf"]);
			$uf = null;
			$datahora = null;

			$data = json_decode(file_get_contents("php://input"));

			if (!$data && $setget) {
				$uf = $_GET["uf"];
				$datahora = $_GET["data"];
			} elseif (!$data && $set) {
				$uf = $_POST["uf"];
				$datahora = $_POST["data"];
			} else {
				// set product property values
				$uf = $data->uf;
				$datahora = $data->data;
			}

			$assistencia->DataHora = $datahora;
			$assistencia->UF = $uf;

			// read products will be here
			// query products
			$stmt = $assistencia->readMulti();
			$num = $stmt->rowCount();

			// check if more than 0 record found
			if ($num > 0) {

				// products array
				$assistencia_arr = array();
				$assistencia_arr["records"] = array();

				// retrieve our table contents
				// fetch() is faster than fetchAll()
				// http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					// extract row
					// this will make $row['name'] to
					// just $name only
					extract($row);

					$assistencia_item = array(
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
}
