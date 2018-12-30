<!DOCTYPE html>
<html lang="en">
<head>
     <title>Index Page</title>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
     <link rel="stylesheet" href="../CSS/style.css">
     <?php
          include_once("classes/DB.php");
          include("login.php");
          include('classes/Login_class.php');

          if (Login_class::isLoggedIn()) {
               echo "logged in";
               echo Login_class::isLoggedIn();
          }
          else{
               echo "not logged in";
          }
     ?>
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
                      <?php if (!Login_class::isLoggedIn()) { ?>

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
                            <a href="change_password.php"><input class="btn" type="button" name="changepassword-btn" value="Change Password"></a>
                          </form>
                      <?php } ?>
                    </ul>
               </div>
          </div>
     </nav>
