<?php
// Start session
session_start();

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $data = [
        'status' => 200,
        'message' => 'User data retrieved successfully',
        'user' => [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role_id' => $_SESSION['role_id'],
        ],
    ];
    header("HTTP/1.0 200 OK");
    echo json_encode($data);
} else {
    $data = [
        'status' => 401,
        'message' => 'Unauthorized. Please log in.',
    ];
    header("HTTP/1.0 401 Unauthorized");
    echo json_encode($data);
}
?>
