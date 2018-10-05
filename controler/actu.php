<?php
include('../model/inc.php');
if (!isset($_SESSION['id']))
	header("Location: ../index.php");

if(isset($_SESSION['lastmsg'])){
	if (check_actu($conn, $_SESSION['lastmsg'], $_SESSION['matchid'])== true)  //si il trouve un nvx message, renvoie true
	{ ?>
		<div id="actu" style="display: none;"></div>
	<?php }
}
if (isset($_SESSION['matchstab'])){
	$matches = $_SESSION['matchstab'];
	foreach($matches as $match){
		echo "<div id='catchme' title='". $match[0]. "' style='display:none'></div>";
		if(check_unseen_msg($conn, $match[0], $match[1]) == true)
		{?>
			<script type="text/javascript">
				var val = document.getElementById('catchme').title;
				// console.log(val);
				if (document.getElementById(val) == null){
					$( "#p" + val ).prepend( "<span id=" + val + " class='badge badge-pill badge-danger'>o</span>" );
				}
				$('#catchme').remove();
			</script>
<?php 	}
		else{?>
			<script type="text/javascript">
				var val = document.getElementById('catchme').title;
				if (document.getElementById(val) != null){
					$('#' + val).remove();
				}
				$('#catchme').remove();
			</script>
<?php	}
	}
}
?>