<?php
include('../model/inc.php');
if (!$_SESSION["pseudo"])
	header("Location: ../index.php");
$myid = $_SESSION['id'];
$anotif = "active";
$restab = check_profile(get_user_data($myid, $conn), $myid, $conn);
if (count($restab) != 0){
	include('header.php');
	include ('../view/no_suggest.php');
}
else{
	update_notifs($conn, $myid);
	$notifs = get_all_notifs($conn, $myid);
	if (count($notifs) == 0)
		$nonotif = "Vous n'avez pas encore de notifications";
	else
		$nonotif = '';
	// var_dump($notifs);
	$parsednotifs = [];
	foreach ($notifs as $notif) {
		$data = get_user_data( $notif['id_auth'],$conn);
		if ($notif['subject'] == 'like'){
			$parsednotifs[] = "<a href='profile.php?user=". $notif['id_auth']."''>". $data['prenom'] . ' ' . $data['nom']."</a> vous à liké !";
		}
		if ($notif['subject'] == 'match'){
			$parsednotifs[] = "<a href='profile.php?user=". $notif['id_auth']."''>". $data['prenom'] . ' ' . $data['nom']."</a> à matché avec vous !";
		}
		if ($notif['subject'] == 'unmatch'){
			$parsednotifs[] = "Vous et <a href='profile.php?user=". $notif['id_auth']."''>". $data['prenom'] . ' ' . $data['nom']."</a> c'est de l'histoire ancienne...";
		}
		if ($notif['subject'] == 'visit'){
			$parsednotifs[] = "<a href='profile.php?user=". $notif['id_auth']."''>". $data['prenom'] . ' ' . $data['nom']."</a> à visité votre profil !";
		}
	}
	$msgs = [];
	$matches = get_matches_id($conn, $_SESSION['id']);
	foreach($matches as $match){
		if (check_unseen_msg($conn, $match[0], $match[1]) == true)
		{
			$data = get_user_data( $match[1],$conn);
			$msgs[] = "<a href='profile.php?user=". $match[1]."''>". $data['prenom'] . ' ' . $data['nom']."</a> vous à envoyé un nouveau message !";
		}
	}
	include('header.php');
	include ('../view/page_notif.php');
}
?>