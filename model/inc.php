<?php

require("../config/database.php");
require ("function.php");

try {

	$conn = new PDO($DB_DSN2, $DB_USER, $DB_PASSWORD);	
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e){
	echo 'Connection failed: ' . $e->getMessage();
}
session_start();
date_default_timezone_set('Europe/Paris');
error_reporting(E_ALL);
ini_set('display_errors', 'on');
if (isset($_SESSION['id']))
	check_log($conn, $_SESSION['id'], date("Y-m-d H:i:s"));
$ahome = '';
$anotif = '';
$achat= '';
$asuggest = '';
$aprofile = '';
$areglages = '';
$alog = '';
?>

