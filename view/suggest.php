<body>
	<?php include("upper_nav.php");?>
	<div class="container-fluid" id = "suggest_container">
		<div class="row">
			<div class="col-1">
			</div>
			<div class="col-9">
				<div class="row">
					<div id="settingscontainer">	
						<h5 style="text-align: center;">Reglages</h5>
						<form class="" action="suggest.php" method="post" id="settingsform">
							<div class="row">
								<div class="form-group">
									<label for="sort">Trier par:</label>	
					                <select class="form-control" name = "sort">
					                    <option <?=$fage?> name="sort" value="age">age</option>
					                    <option <?=$floc?> name="sort" value="localisation">localisation</option>
					                    <option <?=$fpop?> name="sort" value="popularite">popularité</option>
					                    <option <?=$ftag?> name="sort" value="tags">Interets en commun</option>
					                </select>
					            </div>
					            <div class="form-group">
					            	<label id="p_age" for="age"><?=$age_p?></label>
					            	<br>
					                <input  name="age" min="18" max ="100" step="1" type="range" multiple value="<?=$ageval?>">
					            </div>	
	
								<div class="form-group">
					                <label id="p_km" for="distance">Distance max: <?=$kmval?> km</label>
					                <br>
					                <input type="range" max ='1000' value = "<?=$kmval?>" name="distance">
				                </div>
				                <div class="form-group">
				                <label id="p_score" for="pop"><?=$score_p?></label>
				                <br>
				                <input  name="pop" min="0" max ="100" step="1" type="range" multiple value="<?=$scoreval?>">
				                </div>
							
					            <div class="form-group">
					                <label for="tag">Intérêts recherchés:</label>
					                <input class="form-control" value="<?=$tagval?>" type="text" name="tag">
					                <br>
			                	</div>
			                	<input style="margin-right: 10px; margin:auto;" type="submit" name="reset" value="reset">
				                <input style="margin: auto;" type="submit" name="settingsform" value="ok">	
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-2">
			</div>
		</div>	
		<div class="row" id="">
			<div class="col-12">
				<?php $q = 0 ;for($i = 0; $i < 2 ; $i++){?>
				<div class="Grid Grid--gutters Grid--cols-3 u-textCenter">
					<?php for ($b = 0; $b < 3; $b++){ if(isset($six[$q])){?>
					<div class="Grid-cell">
						<div class="card Demo content-1of3">
							<img class ="card-img-top" src="<?=$six[$q]['pp']?>">
							<div class="card-body">
								<h4 class="card-title"><?php echo( $six[$q]['prenom']. " " . $six[$q]['nom']);?></h4>
								<h5 class="cord-subtitle"><?=$six[$q]['age']?>ans, à <?=$suggest[$q + $offset]['km']?>km</h5>
								<h6 class="score">score: <?=$six[$q]['score']?></h6>
								<p class="card-text">
									<?php foreach($six[$q]['tags'] as $tag){
										echo $tag . " ";
									} ?>
								</p>
								<a href='<?="profile.php?user=".$suggest[$q + $offset]['id']?>' class="btn btn-outline-secondary">Voir le profil</a>
							</div>
						</div>
					</div>
					<?php }$q++; } ?>
				</div>
				<?php } ?>
			</div>
		</div>
		<div class="row" id="">
			<div class="col-3">
				
			</div>
			<div class="col-9">
				<form id="pagination" action="suggest.php" method="post">
					<?php if ($count > 1){?>
				  <button id="btn-gauche" class="prev pagi" name="gauche" value="<?=$offset-6?>">❮</button>
				  <?php } ?>
				  <span id ="pagi-number"><?=$count . " / " . $nb_pages?></span>
				  <?php if ($count < $nb_pages){?>
				  <button id="btn-droite" class="next pagi" name="droite" value="<?=$offset+6?>">❯</button>
				  <?php } ?>
				</form>
			</div>
		</div>
	</div>
	<?php include("footer.php");?>
	<script src="../js/suggest.js"></script>
	<script src="../js/multirange.js"></script>
</body>
</html>