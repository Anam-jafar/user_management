<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include('../includes/functions.php');

$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod == 'PUT') {
    parse_str($_SERVER['QUERY_STRING'], $params);
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (empty($inputData)) {
        $inputData = $_POST;
    }

    $updateUser = updateUser($inputData, $params);

    echo $updateUser;

} else {
    $data = [
        'status' => 405,
        'message' => $requestMethod . ' method not allowed',
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}
?>
