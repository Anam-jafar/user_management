<?php
	
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With');

include('../includes/functions.php');

$requestMethod = $_SERVER['REQUEST_METHOD'];

if($requestMethod == "GET"){
    $userList = getUserList();
    echo $userList;
}else{
    $data = [
        'status' => 405,
        'message' => $requestMethod. '  method not allowed',
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}


?>

