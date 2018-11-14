<body>
	<?php include("upper_nav.php");?>
	<div class="container-fluid" id = "suggest_container">
		<div class="row" id="">
			<div class="col-1">
			</div>
			<div class="col-10">
				<h2>
					Oh non... ton profil n'est pas complet :'( Va vite le remplir !
				</h2>
				<h3>Il te manque : </h3>
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