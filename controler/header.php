<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="../ressources/img/favicon.ico" />
	
	<link rel="stylesheet" type="text/css" href="../ressources/style/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../ressources/style/style.css?v=1">
	<link rel="stylesheet" type="text/css" href="../ressources/style/multirange.css">
	<?php if (isset($css) && $css != NULL) {?>
	<link rel="stylesheet" type="text/css" href="../ressources/style/<?=$css?>.css?v=1">
	<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyDtnHLVfWMIe7h9cP2UIS559Jp_CgyUPhU"></script>
	<?php } if (isset($header)){ ?>
	 	<title><?=$header?></title>
	<?php } else { ?>
	 	<title>Matcha</title>
	<?php } ?>

</head>