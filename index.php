<?php
/*
Author: Liuta Romulus Ovidiu
Email: info@thinkovi.com
Version: 1.0
For: bab.la assignment 
*/

error_reporting(E_ALL);
ini_set("display_errors", "On");
ini_set("date.timezone", "Europe/Berlin");
session_start();

define("APPLICATION_PATH",  dirname(__FILE__));
define("DS", DIRECTORY_SEPARATOR );

include_once(APPLICATION_PATH . DS. "application". DS . "app.php");

$app = new App();

echo $app->run();