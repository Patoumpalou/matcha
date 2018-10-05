<body>
   <?php include("upper_nav.php");?>
   <div class="container-fluid" id = "container" >
      <?php if ($beta == true){?>
         <div class="row" id="match">
            <img src="../ressources/img/match.png">
         </div>
      <?php } ?>
      <?php if ($self == false){?>
      <div class="row" id="chepa">
        <form id="specialform" method="POST" action="<?="profile.php?user=".$id?>">
        </form>
        <button form="specialform" type="submit">Signaler <?=$user_data['prenom']?> comme etant un faux compte</button>
        <?php if(check_if_bloqued($conn, $_SESSION['id'], $id) == false){?>
        <button form="specialform" type="submit" name="black">Bloquer <?=$user_data['prenom']?></button>
        <?php }else{?>
        <button form="specialform" type="submit" name="unblack">Débloquer <?=$user_data['prenom']?></button>        
        <?php } ?>
         <p><?=$status?></p>
      </div>
      <?php } ?>
      <div class="row" id= "profile-raw">
         <div class="col-6">
            <form method="POST" action="profile.php" id="formimg" enctype="multipart/form-data">
               <div class="row">
                  <label class="me" for="img1">
                  <img class="pic" src="<?=$pic_names[0]?>">
                  </label>
                  <?php if ($self == true){?>
                  <input class="profile_img" id="img1" type="file" name="pic_file_1" onchange="form.submit()">
                  <?php } if (strcmp("nopic.png", substr($pic_names[0], 27)) != 0 && $self == true){ ?>
                  <span class="close cursor" onclick="del_img('<?=$pic_names[0]?>')">x</span>
                  <?php } ?>
                  <label class="me" for="img2">
                  <img class="pic" src="<?=$pic_names[1]?>">
                  </label>
                  <?php if ($self == true){?>
                  <input class="profile_img" id="img2" type="file" name="pic_file_2" onchange="form.submit()">
                  <?php } if (strcmp("nopic.png", substr($pic_names[1], 27)) != 0 && $self == true){?>
                  <span class="close cursor" onclick="del_img('<?=$pic_names[1]?>')">x</span>
                  <?php } ?>
               </div>
               <div class="row">
                  <label class="me" for="img3">
                  <img class="pic" src="<?=$pic_names[2]?>">
                  </label>
                  <?php if ($self == true){?>
                  <input class="profile_img" id="img3" type="file" name="pic_file_3" onchange="form.submit()">
                  <?php } if (strcmp("nopic.png", substr($pic_names[2], 27)) != 0 && $self == true) {?>
                  <span class="close cursor" onclick="del_img('<?=$pic_names[2]?>')">x</span>
                  <?php } ?>
                  <label class="me" for="img4">
                  <img  class="pic" src="<?=$pic_names[3]?>">
                  </label>
                  <?php if ($self == true){?>
                  <input class="profile_img" id="img4" type="file" name="pic_file_4" onchange="form.submit()">
                  <?php } if (strcmp("nopic.png", substr($pic_names[3], 27)) != 0 && $self == true){?>
                  <span class="close cursor" onclick="del_img('<?=$pic_names[3]?>')">x</span>
                  <?php } ?>
               </div>
               <?php if (isset($errors['caaca'])) { ?>
               <p class="error"> Erreur lors de la copie du fichier</p>
               <?php } if (isset($errors['cacaca'])) { ?>
               <p class="error">Extension invalide</p>
               <?php } ?>
            </form>
            <p style="margin: 20px;">Bio :</p>
            <?php if ($modif == 1){?>
            <textarea class="form-control" name="bio" form="userinfo"><?=$user_data['bio']?></textarea>
            <?php } else { ?>
            <p id='bio'><?=$user_data['bio']?></p>
            <?php } ?>
            <div>
               <p id="demo" title="<?=$user_data['lat']. "," . $user_data['lon']?>"></p>
               <div id="mapholder"></div>
            </div>
         </div>
         <div class="col-6">
            <label id="labelpp" class="me" for="img5">
            <img id= "pp" class="pic" src="<?=$pic_names[4]?>">
            </label>
            <?php if ($self == true){?>
            <input class="profile_img" id="img5" type="file" name="pic_file_5" form="formimg" onchange="form.submit()">
            <?php } if (strcmp("nopic.png", substr($pic_names[4], 27)) != 0 && $self == true){?>
            <span id = "spanprof" class="close cursor" onclick="del_img('<?=$pic_names[4]?>')">x</span>
            <?php } ?>
            <!-- hidden input -->
            <!--  <input form="userinfo" id="jenova" type="text" name="location" value = ""> -->
            <!--  -->
            <?php if ($modif == 1 && $self == true){?>
            <div id="modifprofil"></div>
            <div id="adress" style="display: none;"></div>
            <form class = "form-group" method ="POST" action ="profile.php" id = "userinfo">
               <label for="prenom">Prenom:</label>
               <input class="form-control" type="text" name="prenom" value="<?=$user_data['prenom']?>">
               <br><label for="nom">Nom:</label>
               <input class="form-control" type="text" name="nom" value="<?=$user_data['nom']?>"><br><label for="age">Age:</label>
               <input class="form-control" type="text" name="age" value="<?=$user_data['age']?>"><br><label for="sexe">Genre:</label>
               <div id="gender" class="raw">
                  <!-- how to pre check smartly this shit ?  -->
                  <label class="radio-inline"><input type="radio" name="sexe" value="homme" checked>Homme</label>
                  <label class="radio-inline"><input type="radio" name="sexe" value="femme">Femme</label>
                  <label class="radio-inline"><input type="radio" name="sexe" value="autre">Autre</label>
               </div>
               <br>                      
               <label for="location">Localisation:</label>
               <input id="ineedloc" class="form-control" type="text" name="location" value ="<?=$user_data['location']?>">
               <br>
               <label for="interest">Interets (ex :#matcha):</label>
               <input class="form-control" type="text" name="interest" value ="<?php echo(implode(" ", $user_tags))?>"><br>
               <label for="orientation">Orientation sexuelle:</label>
               <div id="sexuality" class="raw">
                  <select class="form-control" name = "orientation">
                     <!--  this shit too  -->
                     <option name="orientation" value="Heterosexuel">Heterosexuel</option>
                     <option name="orientation" value="Homosexuel">Homosexuel</option>
                     <option name="orientation" value="Bisexuel">Bisexuel</option>
                     <option name="orientation" value="Pansexuel">Pansexuel</option>
                     <option name="orientation" value="Sapiosexuel">Sapiosexuel</option>
                  </select>
               </div>
               <input type="submit" name="ok" value="ok">       
            </form>
            <?php } else { ?>
            <!-- <div class="card"> -->
            <p style="max-width: 100%"><?=$user_data['prenom']?> <?=$user_data['nom']?>, <?=$user_data['age']?> ans</p>
            <p><b>Genre:</b> <?=$user_data['sexe']?></p >
            <p><b>Pseudo:</b> <?=$user_data['pseudo']?></p>
            <p><b>Score:</b> <?=$user_data['score']?></p>
            <?php if ($user_data['location'] != ""){?>
            <p><b>Localisation:</b> <?=$user_data['location']?></p>
            <?php } else {?>
            <p id="adress">Localisation: </p>
            <?php } ?>
            <p><b>Interets:</b> <?php echo(implode(" ", $user_tags))?></p>
            <p><b>Orientation sexuelle:</b> <?=$user_data['orientation']?></p>
            <?php if ($self == true){?>
            <button id="modifprofil">Modifier mon profil</button>
            <?php }} if (isset($_GET['error']) && $_GET['error'] == "loc"){?>
            <p class="error">Adresse invalide</p>
            <?php }?>
            <!--  </div> -->
            <!-- /////////////////////////////////////////////// -->
            <?php if($self == false){?>
            <div id ="love">
               <form id="formlove" action="<?="profile.php?user=".$id?>" method="post">
                  <?php if ($like == false){?>
                  <button id="likebtn" name="like" value="ok">
                     <h4>Like <?=$likeback?></h4>
                     <img src="../ressources/img/coeur.png">    
                  </button>
                  <?php }else {?>
                  <button id="likebtn" class="dislike" name="like" value="ok">
                     <h4>Unlike</h4>
                     <img src="../ressources/img/coeur.png">    
                  </button>
                  <?php } ?>                     
               </form>
            </div>
            <?php } if (isset($errors['pp'])){?>
            <p class="error">Il faut ajouter une photo de profil pour pouvoir liker</p>
            <?php } ?>
            <div>
               <?php if ($self == true){ ?>  
               <h5>Ils t'on visité :</h5>
               <div id="visited">
                  <?php foreach($visits as $someone){?>
                  <a href="<?="profile.php?user=".$someone['id_visit']?>"><img class="imgvisit" src="<?=$someone['img']?>"></a>
                  <?php }?>    
               </div>
               <h5>Ils t'on like :</h5>
               <div id="likes">
                  <?php foreach($likes as $someone){?>
                  <a href="<?="profile.php?user=".$someone['id_author']?>"><img class="imgvisit" src="<?=$someone['img']?>"></a>
                  <?php } ?>
               </div>
               <?php } ?>
            </div>
         </div>
      </div>
   </div>
   <!--  <script type="text/javascript" src="../js/script.js"></script> -->
   <?php include("footer.php");?>
   <script src="../js/profile.js"></script>
</body>
</html>