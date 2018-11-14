<body>
	 <?php include("upper_nav.php");?>
	 <div class="container-fluid" style="margin-top: 20px">
	 	<div class="row">
	 		<div class="col-1"></div>
		 	<div class="col-10-lg">
		 		<p><?=$nonotif?></p>
		 		<?php foreach($msgs as $msg){?>
		 		<div>
		 			<p><?=$msg?></p>
		 		</div>
		 		<?php } ?>
		 		<?php foreach ($parsednotifs as $str){?>
				 	<div>
				 		<p><?=$str?></p>
				 	</div>
			 	<?php }?>	
		 	</div>
		 	 <div class="col-1"></div>
	 	</div>	 	
	 </div>
	  <?php include("footer.php");?>
</body>
