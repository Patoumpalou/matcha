<?php
include('../model/inc.php');
$css = "chat";
$header = "Discute avec tes amours";
if (!isset($_SESSION['id']))
	header("Location: ../index.php");
$id = $_SESSION['id'];
if (isset($_SESSION['matchid'])){
	$matchid = $_SESSION['matchid'];
	$msgs = get_msgs($conn, $matchid);
	$i = 0 ;
	$tmp = 1;
	$otherid = 0;
	foreach($msgs as $msg){
		$date = $msg['udate'];
		$date = $date[8] . $date[9] . "/" . $date[5] . $date[6] . " à " . $date[11] . $date[12] . "h" . $date[14] . $date[15];
		if ($msg['id_author'] != $id)
		{
			$class = "you";
		}else{
			$class="";
		}
	?>
		<li>
		<span>
		<p class = "chef <?=$class?>"><b><?=$msg['name_author']." le ".$date.":"?></b></p>
		<p class="text <?=$class?>"><?=$msg['content']?></p>
		</span>
		</li>
	<?php
		if($id != $msg['id_author'] && $tmp == 1){
			$otherid = $msg['id_author'];
			$tmp = 2;
		}
		$i++; 
	}
	if(isset($msgs[$i-1]['content']))
		$_SESSION['lastmsg'] = $msgs[$i-1]['content'];
	?>
	<script type="text/javascript">
		document.querySelector('#contentbox').scroll(0,1000000);
		var parent =document.getElementById('actucontainer');
		var child = document.getElementById('actu');
		if (child != null)
			parent.removeChild(child);  //si cet element est la, le chat s'actualise
	</script>

	<?php 
	update_msg($conn, $matchid, $otherid);
}
?>