<?php
include_once dirname(__FILE__).'/connect.php';
include_once dirname(__FILE__).'/functions.php';
 
sec_session_start(); // Our custom secure way of starting a PHP session.
 
if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // The hashed password.
 
    if (login($username, $password, $mysqli) == true) {
        // Login success 
        header('Location: ../home.php');
    } else {
        // Login failed 
        header('Location: ../index.php?error=1');
    }
} else {
    // The correct POST variables were not sent to this page. 
    echo 'Invalid Request';
}