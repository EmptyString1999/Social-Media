<?php include('includes/header.php'); ?>
<?php
     if (isset($_POST["submit-btn"])) {
          $username = $_POST["username"];
          $password = $_POST["password"];
          $email = $_POST["email"];

          if(DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username)))
          {
               echo "user already exists";
          }
          else {
               if(strlen($username) >= 3 && strlen($username) <= 255){
                    if(strlen($password) >= 6 && strlen($password) <= 60){
                         if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                              if(!DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))){
                                   DB::query("INSERT INTO users VALUES (\"\", :username, :password, :email, \"\")", array(":username"=>$username, ":password"=>password_hash($password,PASSWORD_BCRYPT), ":email"=>$email));
                                   echo "sucsess";
                              }
                              else{
                                   echo "email in use";
                              }
                         }
                         else{
                              echo "invalid email";
                         }
                    }
                    else {
                         echo "invalid password";
                    }
               }
               else{
                    echo "invalid Username";
               }
          }
     }
?>

     <div class="container-fluid text-center">
          <div class="row title">
               <div class="col-sm-3 text-left"></div>
               <div class="col-sm-6 text-left">
                    <h1 class="title">Register</h1>
                    <hr>
               </div>
               <div class="col-sm-3"></div>
          </div>
          <div class="row content">
               <div class="col-sm-5"></div>
               <div class="col-sm-2 text-left content-center">
                    <form method="post" class="form-buffer">
                         <div class="form-group">
                              <input class="form-control" type="text" name="username" value="" placeholder="Username" />
                         </div>
                         <div class="form-group">
                              <input class="form-control" type="text" name="password" value="" placeholder="Password" />
                         </div>
                         <div class="form-group">
                              <input class="form-control" type="text" name="email" value="" placeholder="Email" />
                         </div>
                         <div class="form-group">
                              <a href="index.php"><input class="btn btn-submit" type="button" name="cancel-btn" value="Cancel" href="index.php"/></a>
                              <input class="btn btn-submit" type="submit" name="submit-btn" value="Create Acount"/>
                         </div>
                    </form>
               </div>
               <div class="col-sm-5"></div>
          </div>
     </div>
     <?php include("includes/footer.php")?>
