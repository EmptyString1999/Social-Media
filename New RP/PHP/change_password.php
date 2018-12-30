<?php
     include_once("classes/DB.php");
     include('classes/Login_class.php');

     if (Login_class::isLoggedIn()) {
          if (isset($_POST['changepassword'])) {
               $oldpassword = $_POST['oldpassword'];
               $newpassword = $_POST['newpassword'];
               $newpasswordrepeat = $_POST['newpasswordrepeat'];
               $userid = Login_class::isLoggedIn();
               if(password_verify($oldpassword, DB::query('SELECT password FROM users WHERE id=:userid', array(':userid'=>$userid))[0]["password"])){
                    if ($newpassword == $newpasswordrepeat) {
                         if(strlen($newpassword) >= 6 && strlen($newpassword) <= 60){
                              DB::query('UPDATE users SET password=:newpassword WHERE id=:userid', array(':newpassword' => password_hash($newpassword,PASSWORD_BCRYPT), ':userid'=>$userid));
                              header('Location: index.php');
                         }
                    } else {
                         echo "passwords do not match";
                    }
               } else {
                    echo "incorrect old password";
               }
          }
     }else{
          $istokenpresent = false;
          $token = $_GET['token'];
          if (isset($token)) {
               $istokenpresent = true;
               if (DB::query('SELECT token FROM password_tokens WHERE token=:token', array(':token'=>$_GET['token']))) {
                    if (DB::query('SELECT user_id FROM password_tokens WHERE token=:token', array(':token'=>$token))[0]['user_id']) {
                         $userid = DB::query('SELECT user_id FROM password_tokens WHERE token=:token', array(':token'=>$token))[0]['user_id'];
                         echo $userid;
                         if (isset($_POST['changepassword'])) {
                              $newpassword = $_POST['newpassword'];
                              $newpasswordrepeat = $_POST['newpasswordrepeat'];
                              if ($newpassword == $newpasswordrepeat) {
                                   if(strlen($newpassword) >= 6 && strlen($newpassword) <= 60){
                                        DB::query('UPDATE users SET password=:newpassword WHERE id=:userid', array(':newpassword' => password_hash($newpassword,PASSWORD_BCRYPT), ':userid'=>$userid));
                                        DB::query('DELETE FROM password_tokens WHERE user_id=:userid', array(':userid'=>$userid));
                                        if (!DB::query('SELECT token FROM password_tokens WHERE user_id=:userid', array(':userid'=>$userid))) {
                                             header("Location: index.php");
                                        } else { die('token not deleted');}

                                        } else {
                                             echo "password is to short";
                                        }
                              } else {
                                   echo 'passwords do not match';
                              }
                         }
                    } else {
                         echo 'user id not found';
                    }
               } else {
                    echo 'password token not found';
               }
          } else {
               header("Location: index.php");
          }
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

          <div class="container-fluid text-center">
               <div class="row title">
                    <div class="col-sm-3 text-left"></div>
                    <div class="col-sm-6 text-left">
                         <h1 class="title">Social Media</h1>
                         <hr>
                    </div>
                    <div class="col-sm-3"></div>
               </div>
               <div class="row content">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8 text-left content-center">
                         <h1>Change your password</h1>
                         <form action="<?php if (!$istokenpresent) { echo 'change_password.php';}else{ echo 'change_password.php?token='.$token.''; }?>" method="post">
                              <?php if (!$istokenpresent) {?><input type="password" name="oldpassword" value="" placeholder="Current Password ..."></br><?php }else{ }?>
                              <input type="password" name="newpassword" value="" placeholder="New Password"></br>
                              <input type="password" name="newpasswordrepeat" value="" placeholder="Repeat New Password"></br>
                              <input type="submit" name="changepassword" value="Change Password">
                         </form>
                    </div>
                    <div class="col-sm-2"></div>
               </div>
          </div>
          <footer class="container-fluid text-center">
               <p>A Website Made By Ethan Price</p>
          </footer>
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
     </body>

</html>
