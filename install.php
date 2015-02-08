<?php
// Check if all requirements are satisfied.
// Check if database exist, otherwise create one.
include_once dirname(__FILE__).'/config.php';
$conn= new mysqli(HOST, USER, PASSWORD);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Create database
$sql = "CREATE DATABASE IF NOT EXISTS logs";
if ($conn->query($sql) === TRUE) {
    echo "Database logs created successfully <br /><br />";
} else {
    echo "Error creating database: " . $conn->error . "<br />";
}

include_once 'includes/connect.php';
$sql = "CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) unsigned NOT NULL auto_increment,
    `username` varchar(255) NOT NULL default '',
    `password` varchar(255) NOT NULL default '',
    PRIMARY KEY  (`ID`)
)";

if($mysqli->query($sql) === TRUE){
    echo "Table users created successfully <br /><br />";
} else {
    echo "Error creating table: " . $mysqli->error. "<br />";
}

$sql = 'INSERT INTO users (username, password) VALUES("msoni", "qwER1234!")';

if($mysqli->query($sql) === TRUE){
    echo "User msoni created successfully <br /><br />";
} else {
    echo "Error creating user: " . $mysqli->error. "<br />";
}

echo "Login Info <br />";
echo "username: msoni<br/>";
echo "password: qwER1234!<br />";
echo "<a href='index.php'>Continue ...</a>";
?>