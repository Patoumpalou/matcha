<?php

include('../model/inc.php');

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
		create_user_data($user['id'], $conn);
		// update_user_data($conn, $user['nom'], "nom", $_SESSION["id"]);
		// update_user_data($conn, $user['prenom'], "prenom", $_SESSION["id"]);
		header("Location: http://localhost:8080/matcha/index.php");
	}
}
else
	header("Location: http://localhost:8080/matcha/index.php");
?>