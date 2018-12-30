<?php
  include_once("classes/DB.php");
if (isset($_POST['resetpassword'])) {
  $cstrong = True;
  $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
  $email = $_POST['email'];
  $user_id = DB::query("SELECT id FROM users WHERE email=:email", array(":email"=>$email))[0]['id'];
  DB::query("INSERT INTO password_tokens VALUES ('', :token, :user_id)", array(":token"=>sha1($token), ":user_id"=>$user_id));
  echo "email sent!";
  echo $token;
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
                               <a href="logout.php"><input class="btn" type="submit" name="logout-btn" value="logout"></a>
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
                    <h1>Forgotten password</h1>
                    <form action="forgot_password.php" method="post">
                      <input type="text" name="email" value="" placeholder="Email ..."></br>
                      <input type="submit" name="resetpassword" value="Reset Password">
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
