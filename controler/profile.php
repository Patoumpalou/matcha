<?php
include('../model/inc.php');
if (!$_SESSION["pseudo"])
	header("Location: ../index.php");

$css = "profile";
$modif = 0;
$errors = [];
$aprofile = "active";
$files = array("pic_file_1", "pic_file_2","pic_file_3","pic_file_4","pic_file_5");
$pic_names = array("../ressources/uploaded_img/nopic.png", "../ressources/uploaded_img/nopic.png", "../ressources/uploaded_img/nopic.png", "../ressources/uploaded_img/nopic.png", "../ressources/uploaded_img/nopic.png");
$i = 0;
if (isset($_GET['user'])){
	if (count(check_profile(get_user_data($_SESSION['id'], $conn), $_SESSION['id'], $conn)) != 0)
		header("Location: profile.php");
	$id = intval($_GET['user']);
	if ($id == $_SESSION['id'])
		header("Location: profile.php");
	update_score($conn, $id, $_SESSION['id'], 'visit');
	add_visit($conn, $_SESSION['id'], $id);
	add_notif($conn, $id, $_SESSION['id'], 'visit');		
	if (($user_data = get_user_data($id, $conn)) == false)
		header("Location: index.php");
	$user_tags = get_user_tags($conn, $id);	
	$header = $user_data['prenom'] . " " . $user_data['nom'];	
	$self = false;	
	
	$like = check_like($conn, $_SESSION['id'], $id); // regarde si tu a liked le profil
	$match = check_like($conn, $id, $_SESSION['id']);
	if ($match == true)
		$likeback = "back";
	else
		$likeback = "";
	$status = get_status($conn, $id);
	if ($like == true && $match == true){
		$beta = add_match($conn, $id, $_SESSION['id']); //si existe pas, cree et return true. Si existe return false;
		add_notif($conn, $id, $_SESSION['id'], 'match');
		update_score($conn, $id, '3', 'more');
		update_score($conn, $_SESSION['id'], '3', 'more');
	}
	else
		$beta = false;
}
else{
	$beta = false;
	$id = $_SESSION['id'];
	$user_data = get_user_data($id, $conn);
	$user_tags = get_user_tags($conn, $id);	
	$header = 'Mon profil';
	$self = true;
	$visits = get_visits($conn, $id);
	$likes = get_likes($conn, $id); // renvoie les id qui t'on liked

}
if (isset($_POST['signal']) && $self == false){
	update_user_data($conn, 1, 'signaled', $id);
	$message = "Utilisateur identifiant ". $id . "signalé par identifiant ".$_SESSION['id'];
	$message = wordwrap($message, 70, "\r\n");
	mail("patoumpalou@hotmail.fr", "Signal faux compte", $message);
}
if (isset($_POST['black']) && $self == false)
	add_or_del_unwanted($conn, $_SESSION['id'], $id, '1');
if (isset($_POST['unblack']) && $self == false)
	add_or_del_unwanted($conn, $_SESSION['id'], $id, '0');
//var_dump($likes);
if (isset($_POST['like']) && $self == false){
	if (get_img_name_by_id_num($conn, $_SESSION['id'], '4') != false){
		if ($like == false){
			update_like($conn, $_SESSION['id'], $id, "add");
			add_notif($conn, $id, $_SESSION['id'], 'like');
			update_score($conn, $id, '2', 'more');
		}
		else if($like == true){
			if ($match == true){
				update_score($conn, $id, '3', 'less');
				update_score($conn, $_SESSION['id'], '3', 'less');
				remove_match($conn, $id, $_SESSION['id']);
				add_notif($conn, $id, $_SESSION['id'], 'unmatch');
			}
			update_score($conn, $id, '2', 'less');
			update_like($conn, $_SESSION['id'], $id, "delete");
		}
		header("Location: http://localhost:8080/matcha/controler/profile.php?user=" . $id);
	}
	else
		$errors['pp'] = "blalbla";
}
// pour afficher le formulaire
if (isset($_GET['modif']) && $id = $_SESSION['id'])
	$modif = 1;
// pour supprimer une photo avec les croix 
if (isset($_GET['img']) && $id == $_SESSION['id'])
{
	$get = filter_var($_GET['img'], FILTER_SANITIZE_STRIPPED);
	$img_name = substr($get, 27);
	if (!remove_image($get, $conn))
			echo "couille dans le burkini";
	header("Location: http://localhost:8080/matcha/controler/profile.php");
}
/// recuperation des infos entrees dans le formulaire ///////////////////////////
if(isset($_POST['ok']) && $_POST['ok'] === 'ok')
{
	$tmp = filter_var($_POST['prenom'], FILTER_SANITIZE_STRIPPED);
	if ($_POST['prenom'] != "" && strcmp($tmp, $user_data['prenom']) != 0){
		update_user($conn,$tmp, "prenom", $id );
	}
	$tmp = filter_var($_POST['nom'], FILTER_SANITIZE_STRIPPED);
	if ($_POST['nom'] != "" && strcmp($tmp, $user_data['nom']) != 0){
		update_user($conn,$tmp, "nom", $id );
	}
	$tmp = parse_age(intval(filter_var($_POST['age'], FILTER_SANITIZE_STRIPPED)));
	if ($_POST['age'] != "" && strcmp($tmp, $user_data['age']) != 0){
		update_user_data($conn,$tmp, "age", $id );
	}
	$tmp = filter_var($_POST['sexe'], FILTER_SANITIZE_STRIPPED);
	if ($_POST['sexe'] != "" && strcmp($tmp, $user_data['sexe']) != 0 && ($tmp == "homme" || $tmp == "femme")){
		update_user_data($conn,$tmp, "sexe", $id );
	}
	$tmp =filter_var($_POST['location'], FILTER_SANITIZE_STRIPPED);
	if ($_POST['location'] != "" && strcmp($tmp, $user_data['location']) != 0){
		$res = get_coords($tmp);
		if ($res != false){
			update_user_data($conn,$tmp, "location", $id );
			update_coords($conn, $id, $res['lat'], $res['lon']);
		}
		else
			$errors['locat'] = "Adresse inccorecte";
	}
	$tmp = filter_var($_POST['interest'], FILTER_SANITIZE_STRIPPED);
	if ($_POST['interest'] != ""){
		$tmp = parse_tags($tmp);
		foreach($tmp as $tag){
			if (in_array($tag, $user_tags) != true)
				add_tag($conn, $id, $tag);
		}
		$del = array_diff($user_tags, $tmp);
		foreach($del as $tag){
			remove_tag($conn, $id, $tag);
		}
	}
	$tmp = filter_var($_POST['orientation'], FILTER_SANITIZE_STRIPPED);
	if ($_POST['orientation'] != "" && strcmp($tmp, $user_data['orientation']) != 0 && parse_gender($tmp) == true){
		update_user_data($conn,$tmp, "orientation", $id );
	}
	$tmp = filter_var($_POST['bio'], FILTER_SANITIZE_STRIPPED);
	if (strcmp($tmp, $user_data['bio']) != 0){
		update_user_data($conn,$tmp, "bio", $id );
	}
	update_wanted($conn, $id);
	if (isset($errors['locat']))
		header("Location: http://localhost:8080/matcha/controler/profile.php?error=loc");	
	header("Location: http://localhost:8080/matcha/controler/profile.php");
}
///////// recuperation du fichier image ///////////////////////////////////////////
foreach ($files as $e)
{
	// si ya un nvx fichier, le remplace, sinon prend ce qui y a dans la bdd
	if (isset($_FILES[$e]) && $_FILES[$e]['error'] == 0)
	{
		$ext = strtolower(  substr(  strrchr($_FILES[$e]['name'], '.')  ,1) );
		if ($ext == "jpg" || $ext == "png")
		{
			$name = "../ressources/uploaded_img/" . uniqid() . "." . $ext;
			$res = move_uploaded_file($_FILES[$e]['tmp_name'], $name);
			if ($res){
				if (!remove_image_with_num($conn, $num, $id))
					echo "couille dans la puree";
				add_img_to_db($conn, $id, $name, $i);

				header("Location: http://localhost:8080/matcha/controler/profile.php");
			}
			else
				$errors['caaca'] = "Erreur lors de la copie du fichier";
		}
		else{
			$errors["cacaca"] = "extension invalide: " . $ext;
		}
	}
	else{
		$img_path = get_img_name_by_id_num($conn, $id, $i);
		if ($img_path !== false){
			$pic_names[$i] = $img_path;
		}
	}
	$i++;
}
$radiogender = ft_radiogender($user_data['sexe']);
$ftorientation = ft_optionorientation($user_data['orientation']);
// $pic_names = get_pic_name_by_id($id, $conn);
// $pic_names = array_pic_name($pic_names);
include('header.php');
include ('../view/page_profile.php');

// atttention quand tu modifie la bdd, modifie bien le cookie syst si tu l utilise comme ref 

// no more than 5imgs 
?>

