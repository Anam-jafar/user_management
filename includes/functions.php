<?php

require '../includes/db.php';

function getUserList(){
    global $conn;

    session_start();
    if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
        return __error403("Only admin can access the user list.");
    }

    $query = "SELECT * FROM users";
    $query_run = mysqli_query($conn, $query);

    if($query_run){
        if(mysqli_num_rows($query_run)>0){
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
            $data = [
                'status' => 200,
                'message' => 'User List Fetched Successfully',
                'data' => $res
            ];
            header("HTTP/1.0 200 OK");
            return json_encode($data);
        }else{
            $data = [
                'status' => 404,
                'message' => 'No User Found',
            ];
            header("HTTP/1.0 404 Not Found");
            return json_encode($data);
        }
    }else{
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }
}

function __error422($message){
    $data = [
        'status' => 422,
        'message' => $message,
    ];
    header("HTTP/1.0 422 Unprocessable Entity");
    echo json_encode($data);
}

function __error403($message){
    $data = [
        'status' => 403,
        'message' => $message,
    ];
    header("HTTP/1.0 403 Forbidden");
    echo json_encode($data);
}

function saveUser($input){
    global $conn;

    session_start();
    if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
        return __error403("Only admin can add users.");
    }

    $username = mysqli_real_escape_string($conn, $input['username']);
    $password = mysqli_real_escape_string($conn, $input['password']);

    if(empty(trim($username))){
        return __error422("Enter your username here");
    }elseif(empty(trim($password))){
        return __error422("Enter your password here");
    }else{
        $password = password_hash($password, PASSWORD_DEFAULT);
        $role_id = isset($input['role_id']) ? $input['role_id'] : 2;

        $query = "INSERT INTO users (username, password, role_id) VALUES ('$username', '$password', '$role_id')";
        $res = mysqli_query($conn, $query);

        if($res){
            $data = [
                'status' => 201,
                'message' => 'User Created Successfully',
            ];
            header("HTTP/1.0 201 Created");
            return json_encode($data);
        }else{
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }
    }
}

function updateUser($input, $param) {
    global $conn;

    session_start();
    if (!isset($_SESSION['user_id'])) {
        return __error403("Unauthorized access.");
    }

    if (!isset($param['id'])) {
        return __error422("User ID not found in URL. Enter the user ID.");
    }

    $id = mysqli_real_escape_string($conn, $param['id']);
    $username = isset($input['username']) ? mysqli_real_escape_string($conn, $input['username']) : '';
    $password = isset($input['password']) ? mysqli_real_escape_string($conn, $input['password']) : '';
    $role_id = isset($input['role_id']) ? mysqli_real_escape_string($conn, $input['role_id']) : null;

    if (empty(trim($id))) {
        return __error422("User ID must be provided to update the user.");
    } elseif (empty(trim($username)) && empty(trim($password)) && $role_id === null) {
        return __error422("Provide at least one field to update.");
    } elseif ($_SESSION['role_id'] != 1 && $_SESSION['user_id'] != $id) {
        return __error403("You can only edit your own details.");
    } elseif ($_SESSION['role_id'] != 1 && $role_id !== null) {
        return __error403("Only admin can change roles.");
    } else {
        $updateFields = [];
        if (!empty(trim($username))) {
            $updateFields[] = "username='$username'";
        }
        if (!empty(trim($password))) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $updateFields[] = "password='$password'";
        }
        if ($role_id !== null) {
            $updateFields[] = "role_id='$role_id'";
        }

        $updateQuery = implode(", ", $updateFields);
        $query = "UPDATE users SET $updateQuery WHERE id='$id'";
        $res = mysqli_query($conn, $query);

        if ($res) {
            $data = [
                'status' => 200,
                'message' => 'User Updated Successfully',
            ];
            header("HTTP/1.0 200 OK");
            return json_encode($data);
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }
    }
}

function deleteUser($param) {
    global $conn;

    session_start();
    if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
        return __error403("Only admin can delete users.");
    }

    if (!isset($param['id'])) {
        return __error422("User ID not found in URL. Enter the user ID.");
    }

    $id = mysqli_real_escape_string($conn, $param['id']);

    if (empty(trim($id))) {
        return __error422("User ID must be provided to delete the user.");
    } else {
        $query = "DELETE FROM users WHERE id='$id'";
        $res = mysqli_query($conn, $query);

        if ($res) {
            $data = [
                'status' => 200,
                'message' => 'User Deleted Successfully',
            ];
            header("HTTP/1.0 200 OK");
            return json_encode($data);
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }
    }
}

function loginUser($input) {
    global $conn;

    $username = mysqli_real_escape_string($conn, $input['username']);
    $password = mysqli_real_escape_string($conn, $input['password']);

    if (empty(trim($username))) {
        return __error422("Enter your username here.");
    } elseif (empty(trim($password))) {
        return __error422("Enter your password here.");
    } else {
        $query = "SELECT * FROM users WHERE username='$username'";
        $res = mysqli_query($conn, $query);
        $user = mysqli_fetch_assoc($res);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['username'] = $user['username'];

            $data = [
                'status' => 200,
                'message' => 'Login Successful',
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role_id' => $user['role_id'],
                ],
            ];
            header("HTTP/1.0 200 OK");
            return json_encode($data);
        } else {
            $data = [
                'status' => 401,
                'message' => 'Invalid Username or Password',
            ];
            header("HTTP/1.0 401 Unauthorized");
            return json_encode($data);
        }
    }
}

function registerUser($input) {
    global $conn;

    $username = mysqli_real_escape_string($conn, $input['username']);
    $password = mysqli_real_escape_string($conn, $input['password']);
    $role_id = isset($input['role_id']) ? mysqli_real_escape_string($conn, $input['role_id']) : 2;

    if (empty(trim($username))) {
        return __error422("Enter your username here.");
    } elseif (empty(trim($password))) {
        return __error422("Enter your password here.");
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, password, role_id) VALUES ('$username', '$password', '$role_id')";
        $res = mysqli_query($conn, $query);

        if ($res) {
            $user_id = mysqli_insert_id($conn);
            session_start();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role_id'] = $role_id;
            $_SESSION['username'] = $username;

            $data = [
                'status' => 201,
                'message' => 'User Registered Successfully',
                'user' => [
                    'id' => $user_id,
                    'username' => $username,
                    'role_id' => $role_id,
                ],
            ];
            header("HTTP/1.0 201 Created");
            return json_encode($data);
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }
    }
}
