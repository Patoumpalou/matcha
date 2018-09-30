<?php 
include("../model/inc.php");
$header = "matcha";
$css ="forgotpw";
$ok = false;
$errors = [];
if (isset($_POST['submit']) && $_POST['submit'] === "OK")
{
	if ($_POST['pseudo'] !== "" && $_POST['email'] !== "")
	{
		$user = [];
		$user['pseudo'] = htmlspecialchars($_POST['pseudo']);
		$user['email'] = htmlspecialchars($_POST['email']);
		if (!($user_db = get_user_by($conn, $user['pseudo'], "pseudo")))
		{
			$errors['pseudo'] = 'pseudo inconnu';
		}
		if (!check_email($user['email']))
			$errors['00'] = "Email invalide";
		if (strcmp($user_db['email'], $user['email']) != 0)
			$errors['7654'] = "L'adresse mail et le pseudo ne correspondent pas";
		if (count($errors) == 0)
		{

			if (($row = get_user_by($conn, $user['pseudo'], "pseudo")) != false)
			{
				$id = $row['id'];
				$newpass = uniqid();
				if (!update_user($conn, hash("whirlpool", $newpass), "password", $id))
					$errors['999'] = "Erreur lors du changement du mdp";
				else
				{
					$message = "Bonjour " . $user['pseudo'] . "\r\nVoici votre nouveau mot de passe :\r\n " . $newpass . "\r\nhttp://localhost:8080/matcha/controler/signin.php";
					$message = wordwrap($message, 70, "\r\n");
					if (mail($user['email'], 'Nouveau mot de passe matcha', $message) == false)
						$errors['666'] = "Probleme d'envoi du mail";
					$ok = true;
				}
			}
			else
				$errors['4'] = "Cet email est associe a aucun compte !";
		}
	}
	else
		$errors['plop'] = "Veuillez remplir tous les champs";
}
include( "header.php") ;
include('../view/forgotpw.php');
?>
