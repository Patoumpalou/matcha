<body>
	<script type="text/javascript">

	</script>
	<?php include("upper_nav.php");?>	
	<?php include("footer.php");?>
	<div class="container-fluid">
		<div class="chatrow row">
			<DIV class="col-4" id="contactcol">
			<p><?=$nolove?></p>
			<?php foreach($matches as $key => $someone){
			if ($someone[0] == $matchid){$class = "here";}else{$class="";}?>
			<div class="card matches <?=$class?>" id="p<?=$someone[0]?>">
				<?php if ($notifs[$key] == true){?>
				<span id="<?=$someone[0]?>" class="badge badge-pill badge-danger">o</span>
				<?php }?>
				<a href="chat.php?damn=<?=$someone[0]?>"><?=$someone['prenom'] . " " . $someone['nom']?></a>
			</div>
			<?php }?>
			<div id='actucontainer'></div>
			</DIV>

			<div class="col-8" id = "chatcol">
				<div id="chatbox">
					<div id="contentbox">
					
					</div>
					<form id="msgform" method="post" action="chat.php?damn=<?=$matchid?>">
						<div class="form-group">
							<label for="msg"></label>
							<input class="form-control" type="textarea" name="msg" placeholder="Blablabla">
						</div>
						<input class="btn btn-secondary" type="submit" name="msgsubmit">
					</form>
				</div>
			</div>
		</div>
	</div>
	<script src="../js/chat.js"></script>
</body>
</html>