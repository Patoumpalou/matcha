<header class="topnav" id="myTopnav">
	<a href="javascript:void(0);" class="icon" onclick="myFunction()">
	    <i class="fa fa-bars"></i>
	</a>
	
	<?php if(isset($_SESSION['id'])){?>
	<a href="logout.php">Deconnexion</a>
	<a href="settings.php" class='<?=$areglages?>'>Reglages</a>
	<a href="profile.php" class='<?=$aprofile?>'>Profile</a>
	<a href="suggest.php" class='<?=$asuggest?>'>Suggestions</a>
	<a href="chat.php" class='<?=$achat?>'>Chat</a>
	<a href="notif.php" id ="bell" class='<?=$anotif?>'>
		<img src="../ressources/img/bell.png">
	</a>
	<?php }else{ ?>
	<a href="sign.php" class='<?=$alog?>'>Connexion / Inscription</a>
	<?php } ?>
	<a id='matchalogo' href="../index.php" class="<?=$ahome?>">Matcha</a>

</header>
<div id="omegabox" style="display: none;">	
</div> 
