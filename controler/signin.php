<?php
require('../model/inc.php');
$header = "Connectes toi !";
$css = "signin";
$errors= [];
if (isset($_SESSION["pseudo"]))
	header("Location: ../index.php");
if (isset($_POST['submit']) && $_POST['submit'] === "OK")
{
	if ($_POST['pseudo'] !== "" && $_POST['password'] !== "")
	{
		$pseudo = htmlspecialchars($_POST['pseudo']);
		$hashed = hash("whirlpool", $_POST['password']);
		if (!($user = get_user_by($conn, $pseudo, 'pseudo')))
		{
			$errors['pseudo'] = 'pseudo invalide';
		}
		if (strcmp($hashed, $user['password']) != 0)
			$errors['mdp'] = 'mot de passe invalide';
		else
		{
			if ($user['actif'] != 1)
				$errors['actif'] = "compte pas validé";
			else
			{
				$_SESSION["pseudo"] = $user['pseudo'];
				$_SESSION["id"] = $user['id'];
				$_SESSION["option"] = true;
				$_SESSION['email']= $user["email"];
				header("Location: http://localhost:8080/matcha/index.php");
			}
		}
	}
	else
		$errors['plop'] = "Veuillez remplir tous les champs";
}
include("header.php");
include ('../view/signin.php');
?>

