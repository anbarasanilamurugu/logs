<?php
include_once dirname(__FILE__) .'/includes/connect.php';
include_once dirname(__FILE__) .'/includes/functions.php';
sec_session_start();
 
if (login_check($mysqli) == false) {
	header('Location:index.php');
}

/**
 * Require the library
 */
require 'Log.class.php';
/**
 * Initilize a new instance of PHPTail
 * @var PHPTail
 */
$tail = new Log(APACHE_PATH);

/**
 * We're getting an AJAX call
 */
if(isset($_GET['ajax']))  {
        echo $tail->getNewLines($_GET['pagenum']);
        die();
}
/**
 * Regular GET/POST call, print out the GUI
 */
$tail->generateGUI();