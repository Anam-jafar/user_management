<?php
// includes/db.php

$host = 'localhost';
$db   = 'user_management';
$user = 'root';
$pass = '';

$conn = mysqli_connect($host, $user, $pass, $db);

if(!$conn){
    die('Connection Failed : '. mysqli_connect_error());
}
?>
