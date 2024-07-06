<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include('../includes/functions.php');

$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod == 'DELETE') {
    parse_str($_SERVER['QUERY_STRING'], $params);

    // Debugging: Log received input
    file_put_contents('php://stderr', print_r($params, TRUE));

    $deleteUser = deleteUser($params);

    echo $deleteUser;

} else {
    $data = [
        'status' => 405,
        'message' => $requestMethod . ' method not allowed',
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}
?>
