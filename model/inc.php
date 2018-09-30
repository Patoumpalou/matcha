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

if(isset($_SESSION['pseudo']))
{
	if (check_if_pseudo_is_in_bdd($_SESSION['pseudo'], $conn) === false)
		$_SESSION['pseudo'] = null;
}
date_default_timezone_set('Europe/Paris');
error_reporting(E_ALL);
ini_set('display_errors', 'on');

?>

