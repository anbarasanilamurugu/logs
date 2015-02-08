<?php
mysqli_report(MYSQLI_REPORT_STRICT);
include_once dirname(__FILE__) .'/config.php';
try {
	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
} catch (Exception $e) {
	header('Location:install.php');
}