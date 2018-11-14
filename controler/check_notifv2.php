<?php 
require("../config/database.php");
require("../model/function.php");
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


// L’utilisateur a reçu un “like”.
// • L’utilisateur a reçu une visite.
// • L’utilisateur a reçu un message.
// • Un utilisateur “liké” a “liké” en retour.
// • Un utilisateur matché ne vous “like” plus.

$matches = get_matches_id($conn, $_SESSION['id']);
$notifcount = 0;
foreach($matches as $match){
	if (check_unseen_msg($conn, $match[0], $match[1]) == true)
		$notifcount++;
}
// echo PHP_EOL. $notifcount;
$getnotif = get_notifs($conn, $_SESSION['id']);
// var_dump($getnotif);
$notifcount = $notifcount + count($getnotif);
// echo PHP_EOL. $notifcount;
if ($notifcount > 0)
	echo "<div id='omega' title=". $notifcount . " style='display:none'></div>";
?>
<script type="text/javascript">
	var val = document.getElementById('omega');
	if (val != null){
		$('#notifspan').remove();
		$('#bell').prepend("<span id='notifspan'>"+val.title+"</span>")
		$('#omega').remove();
	}
</script>


