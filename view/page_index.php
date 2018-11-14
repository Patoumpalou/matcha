<body>
	<header class="topnav" id="myTopnav">
		<a href="javascript:void(0);" class="icon" onclick="myFunction()">
		    <i class="fa fa-bars"></i>
		</a>
		
		<?php if(isset($_SESSION['id'])){?>
		<a href="controler/logout.php">Deconnexion</a>
		<a href="controler/settings.php" >Reglages</a>
		<a href="controler/profile.php" '>Profile</a>
		<a href="controler/suggest.php" >Suggestions</a>
		<a href="controler/chat.php" >Chat</a>
		<a href="controler/notif.php" id ="bell" >
			<img src="ressources/img/bell.png">
		</a>
		<?php }else{ ?>
		<a href="controler/sign.php" >Connexion / Inscription</a>
		<?php } ?>
		<a id='matchalogo' href="index.php" >Matcha</a>

	</header>
	<div id="omegabox" style="display: none;">	
	</div> 
	<script type="text/javascript">
		function myFunction() {
		    var x = document.getElementById("myTopnav");
		    if (x.className === "topnav") {
		        x.className += " responsive";
		    } else {
		        x.className = "topnav";
		    }
		}
	</script>


 <div class="container">
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
      <li data-target="#myCarousel" data-slide-to="3"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">

      <div class="item active">
        <img src="ressources/img/6.jpg" alt="Los Angeles" style="width:100%;">
        <div class="carousel-caption">
          <h3>Rencontrez l'amour</h3>
          <p>Matcha est le meilleur site du monde pour ça</p>
        </div>
      </div>

      <div class="item">
        <img src="ressources/img/5.jpg" alt="Chicago" style="width:100%;">
        <div class="carousel-caption">
          <h3>Plan cul près de chez toi</h3>
          <p>Promo sur les capotes</p>
        </div>
      </div>
    
      <div class="item">
        <img src="ressources/img/3.jpg" alt="New York" style="width:100%;">
        <div class="carousel-caption">
          <h3>L'amour cest vraiment beau. On adore ça ici</h3>
          <p>Trouvez votre ame soeur</p>
        </div>
      </div>
      <div class="item">
        <img src="ressources/img/8.jpg" alt="New York" style="width:100%;">
        <div class="carousel-caption">
          <h3>C'est pas toujours la joie mais bon</h3>
          <p></p>
        </div>
      </div>
  
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>

<div id ="footer">
		<script type="text/javascript">
		function myFunction() {
		    var x = document.getElementById("myTopnav");
		    if (x.className === "topnav") {
		        x.className += " responsive";
		    } else {
		        x.className = "topnav";
		    }
		}

		setInterval(function(){
				$('#omegabox').load('controler/check_notifv2.php');
		}, 1000);

		</script>
	</div>

</body>
