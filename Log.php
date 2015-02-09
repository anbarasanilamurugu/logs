<?php
include_once dirname(__FILE__) .'/includes/connect.php';
include_once dirname(__FILE__) .'/includes/functions.php';
sec_session_start();
 
if (login_check($mysqli) == false) {
	header('Location:index.php');
}

require 'Log.class.php';

$log = new Log(APACHE_PATH);


if(isset($_GET['ajax']))  {
        echo $log->getNewLines($_GET['pagenum']);
        die();
}

$log->generateGUI();
