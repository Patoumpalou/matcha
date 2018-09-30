<?php
require("database.php");

try{
	$db = new PDO($DB_DSN1, $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "drop database if exists matcha ;";
	$db->exec($sql);
	
} catch (PDOException $e){
	echo 'Connection failed: ' . $e->getMessage();
	die();
}
require("setup.php");
?>
