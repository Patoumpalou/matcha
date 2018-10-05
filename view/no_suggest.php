<body>
	<?php include("upper_nav.php");?>
	<div class="container-fluid" id = "suggest_container">
		<div class="row" id="">
			<div class="col-1">
			</div>
			<div class="col-10">
				<h2>
					va bien niquer ta mere et rempli ton profile de gland
				</h2>
				<h3>Il vous manque : </h3>
				<?php foreach($restab as $miss){?>
					<h4>- <?=$miss?></h4>
				<?php }?>
			</div>
			<div class="col-1">
			</div>	
		</div>
	</div>
	<?php include("footer.php");?>
</body>
</html>