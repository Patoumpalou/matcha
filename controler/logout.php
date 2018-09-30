<?php 
include('../model/inc.php');
$header = "Connectes toi !";
$css = "signin";
session_destroy();
header("Location: http://localhost:8080/matcha/index.php");
?>