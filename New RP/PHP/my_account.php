<?php
     include_once("classes/DB.php");
     include('login.php');
     include('classes/Login_class.php');
     include('classes/Image.php');

     $username = "";
     $isFollowing = false;
     $verified = false;
     $usersProfile = false;
     if (Login_class::isLoggedIn()) {
          $userid = Login_class::isLoggedIn();
     }
     else{
          die("not logged in");
     }

     //profile image upload
     if (isset($_POST['uploadprofileimg'])) {
          Image::img_upload('profileimg', 'UPDATE users SET profileimg = :profileimg WHERE id=:userid', array(':userid'=> $userid));
     }
     //cover photo upload
     if (isset($_POST['uploadcoverimg'])) {
          Image::img_upload('coverimg' ,'UPDATE users SET coverimg = :coverimg WHERE id=:userid', array(':userid'=> $userid));
     }
?>
<!DOCTYPE html>
<html lang="en">
     <head>
          <title>Index Page</title>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
          <link rel="stylesheet" href="../CSS/style.css">
          <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
          <link href="//cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">
          <link href="//cdn.quilljs.com/1.3.6/quill.core.css" rel="stylesheet">
          <script src="jquery-3.3.1.min.js"></script>
     </head>
     <body>
          <nav class="navbar navbar-inverse">
               <div class="container-fluid">
                    <div class="navbar-header">
                         <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                              <span class="icon-bar"></span>
                              <span class="icon-bar"></span>
                              <span class="icon-bar"></span>
                         </button>
                         <a class="navbar-brand" href="#">Logo</a>
                    </div>
                    <div class="collapse navbar-collapse" id="myNavbar">
                         <ul class="nav navbar-nav">
                              <li class="active"><a href="#">Home</a></li>
                              <li><a href="#">About us</a></li>
                              <li><a href="#">Contact us</a></li>
                         </ul>
                         <ul class="nav navbar-nav navbar-right forms">
                              <?php if (!isset($_COOKIE['SNID'])) { ?>

                              <form class="form-inline" method="post">
                                   <label for="email" class="email-label">Email:</label>
                                   <input type="text" name="email" class="form-control" id="email">
                                   <label for="pwd" class="pwd-label">Password:</label>
                                   <input type="password" name="password" class="form-control" id="pwd">
                                   <input class="btn" type="submit" name="login-btn" value="login"></input>
                                   <a href="register.php"><input type="button" class="btn btn-visited" value="Register"></a>

                              </form>
                              <?php } else { ?>
                              <form class="form-inline" method="post">
                                   <a href="logout.php"><input class="btn" type="button" name="logout-btn" value="logout"></a>
                              </form>
                              <?php } ?>
                         </ul>
                    </div>
               </div>
          </nav>

          <?php if(Login_class::isLoggedIn()){?>

          <div class="container-fluid text-center">
               <div class="row title">
                    <div class="col-sm-3 text-left"></div>
                    <div class="col-sm-6 text-left">
                         <h1 class="title">Social Media</h1>
                         <hr>
                    </div>
                    <div class="col-sm-3"></div>
               </div>
               </br>
               <div class="row ">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10 text-left content-center">
                         <h1>My account</h1>
                         <h3>please note we use imgur for image hosting to save space on our servers, we are therefore not resposable for losses of images, please back up everything</h3>
                         <form action="my_account.php" method="post" enctype="multipart/form-data">
                              <span>Upload a profile image:</span></br>
                              <input type="file" name="profileimg">
                              <input type="submit" name="uploadprofileimg" value="Upload Profile Image">
                         </form>
                         <hr>
                         </br>
                         <form action="my_account.php" method="post" enctype="multipart/form-data">
                              <span>Upload a cover photo:</span></br>
                              <input type="file" name="profileimg">
                              <input type="submit" name="uploadcoverimg" value="Upload Cover Image">
                         </form>
                         <hr>
                         </br>
          <footer class="container-fluid text-center">
               <p>A Website Made By Ethan Price</p>
          </footer>
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
     </body>
<?php }else{ header("Location: index.php"); }?>

</html>
