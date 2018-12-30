<?php include('includes/header.php');
     if (Login_class::isLoggedIn()) {
          header("Location: newsfeed.php");
     }
?>
     <div class="container-fluid text-center">
          <div class="row title">
               <div class="col-sm-3 text-left"></div>
               <div class="col-sm-6 text-left">
                    <h1 class="title">Social Media</h1>
                    <hr>
               </div>
               <div class="col-sm-3"></div>
          </div>
          <?php include("includes/carousel.php")?>
          <hr>
          <div class="row content">
               <div class="col-sm-3"></div>
               <div class="col-sm-6 text-left content-center">
                    <h1>Welcome</h1>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                         Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                         quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    <hr>
               </div>
               <div class="col-sm-1"></div>
               <div class="col-sm-1 sidenav">
                    <p><a href="#">Link</a></p>
                    <p><a href="#">Link</a></p>
                    <p><a href="#">Link</a></p>
               </div>
               <div class="col-sm-1"></div>
          </div>
     </div>
     <?php include("includes/footer.php")?>
