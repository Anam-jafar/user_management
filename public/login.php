<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Start session
session_start();

include('../includes/functions.php');

$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod == 'POST') {
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (empty($inputData)) {
        $inputData = $_POST;
    }

    // Debugging: Log received input
    file_put_contents('php://stderr', print_r($inputData, TRUE));

    $loginUser = loginUser($inputData);

    echo $loginUser;

} else {
    $data = [
        'status' => 405,
        'message' => $requestMethod . ' method not allowed',
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}
?>
