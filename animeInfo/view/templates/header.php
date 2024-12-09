<header>
   <?php
   if ($title == "login" || $title == "signUp") {
   ?>
      <div class="title2 title">
         <a href="../controller/indexController.php"><img src="../view/assets/images/logoFinal2.png" alt=""><span>AnimeInfo</span></a>
      </div>

      <div class="menu-icon" id="menuIcono">
         <div class="bar"></div>
         <div class="bar"></div>
         <div class="bar"></div>
      </div>

      <nav class="mobile-nav" id="navegadorMovil">
         <ul>
            <li><a href="../controller/indexController.php"><i class="bi bi-house-fill"></i>Home</a></li>
         </ul>
      </nav>

   <?php
   } else {
   ?>

      <div class="title">
         <a href="../controller/indexController.php"><img src="../view/assets/images/logoFinal2.png" alt=""><span>AnimeInfo</span></a>
      </div>
      <div>
      <?php

      if (!empty($_SESSION['usuario'])) {
         if ($_SESSION['rol'] == 'admin') {
            ?>
            <span class='admin' >admin<span>
            <?php
         }
      }
      ?>

      </div>

      <?php
      if (empty($_SESSION['usuario'])) {
      ?>
         <nav class="desktop-nav">
            <ul>
               <li><a href="../controller/loginController.php" class="login"><span>Login</span></a></li>
               <li><a href="../controller/signUpController.php" class="signUp"><span>Sign Up</span></a></li>
            </ul>
         </nav>
      <?php
      } else {
      ?>
         <nav class="desktop-nav">
            <ul>
               <li><a href="../controller/perfilController.php"><i class="bi bi-person-fill"></i></a></li>
               <li><a href="../controller/listasController.php"><i class="bi bi-card-list"></i></a></li>
               <li><a href="../controller/meGustaController.php"><i class="bi bi-heart-fill"></i></a></li>
            </ul>
         </nav>
      <?php
      }
      ?>

      <div class="menu-icon" id="menuIcono">
         <div class="bar"></div>
         <div class="bar"></div>
         <div class="bar"></div>
      </div>

      <?php
      if (empty($_SESSION['usuario'])) {
      ?>
         <nav class="mobile-nav" id="navegadorMovil">
            <ul>
               <li><a href="../controller/loginController.php"><span>Login</span></a></li>
               <li><a href="../controller/signUpController.php"><span>Sign Up</span></a></li>
            </ul>
         </nav>
      <?php
      } else {
      ?>
         <nav class="mobile-nav" id="navegadorMovil">
            <ul>
               <li><a href="../controller/indexController.php"><i class="bi bi-house-fill"></i>Home</a></li>
               <li><a href="../controller/perfilController.php"><i class="bi bi-person-fill"></i>Profile</a></li>
               <li><a href="../controller/listasController.php"><i class="bi bi-card-list"></i>Lists</a></li>
               <li><a href="../controller/meGustaController.php"><i class="bi bi-heart-fill"></i></i>Favorites</a></li>
            </ul>
         </nav>
   <?php
      }
   }
   ?>
</header>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../view/assets/scripts/hamburguerMenu.js"></script>
<script src="../view/assets/scripts/aceptarCookie.js"></script>
