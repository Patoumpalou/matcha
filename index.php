<?php

require("config/database.php");
require("model/function.php");
// include('model/inc.php');
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

$header = "Matcha";
$css = "index";
if (isset($_GET['code']))
{
	$logkey = $_GET['code'];
	if (!($user = get_user_by($conn, $logkey, 'logkey')))
		header("Location: http://localhost:8080/matcha/index.php");
	else if (!update_actif($logkey, $conn))
		header("Location: signin.php");
	else
	{
		$_SESSION["pseudo"] = $user['pseudo'];
		$_SESSION["email"] = $user['email'];
		$_SESSION["id"] = $user['id'];
		$_SESSION['profile'] = false;
		create_conns($conn, $_SESSION['id']);
		update_conns($conn, $_SESSION['id'], date("Y-m-d H:i:s"), '1');
		// update_user_data($conn, $user['nom'], "nom", $_SESSION["id"]);
		// update_user_data($conn, $user['prenom'], "prenom", $_SESSION["id"]);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="ressources/img/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="ressources/style/style.css?v=1">
	<link rel="stylesheet" type="text/css" href="ressources/style/multirange.css">
	
	<link rel="stylesheet" type="text/css" href="ressources/style/bootstrap.min.css">
	<!-- font awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- google api -->
	<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyDtnHLVfWMIe7h9cP2UIS559Jp_CgyUPhU"></script>
	<!-- jquery -->
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<!-- bootstrap -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<?php if (isset($css) && $css != NULL) {?>
		<link rel="stylesheet" type="text/css" href="ressources/style/<?=$css?>.css?v=1">
	<?php } if (isset($header)){ ?>
	 	<title><?=$header?></title>
	<?php } else { ?>
	 	<title>Matcha</title>
	<?php } ?>

</head>
<?php 
include ('view/page_index.php');
?>
