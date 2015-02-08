<?php
include_once dirname(__FILE__) .'/includes/connect.php';
include_once dirname(__FILE__) .'/includes/functions.php';
sec_session_start();
 
if (login_check($mysqli) == true) {
    echo '<p style="text-align:right;"> <a href="includes/logout.php">Log out</a>.</p>';
} else {
    header('Location:index.php');
}

require 'Log.class.php';
$log = new Log(APACHE_PATH);
$log->generateGUI();