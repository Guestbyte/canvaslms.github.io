<?php
require_once 'meekrodb.2.3.class.php';
DB::$user = 'mathe046_dw';
DB::$password = 'dw_mathema';
DB::$dbName = 'mathe046_dw';

require("canvaslib.php");

$api = new CanvasLMS($token, $domain);

$method = $_SERVER['REQUEST_METHOD'];
$params = explode('/', trim($_SERVER['PATH_INFO'], '/'));
//$env = $params[0];
$endpoint = $_SERVER['PATH_INFO'];

switch ($method) {
    case 'GET':
        echo json_encode($api->getlist('/api/v1' . $endpoint . '?', 'id', '', -1), JSON_UNESCAPED_UNICODE);
        die();
        break;
    case 'PUT':
        echo json_encode("PUT", JSON_UNESCAPED_UNICODE);
        die();
        break;
    case 'POST':
        echo json_encode("POST", JSON_UNESCAPED_UNICODE);
        die();
        break;
    case 'DELETE':
        echo json_encode("DELETE", JSON_UNESCAPED_UNICODE);
        die();
        break;
}