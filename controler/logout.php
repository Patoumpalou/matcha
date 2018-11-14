<?php 
include('../model/inc.php');
$header = "Connectes toi !";
$css = "signin";
update_conns($conn, $_SESSION['id'], date("Y-m-d H:i:s"), '0');
session_destroy();
header("Location: ../index.php");
?>