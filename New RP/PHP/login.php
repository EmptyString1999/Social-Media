<?php
     if(isset($_POST["login-btn"])){
          $email = $_POST["email"];
          $password = $_POST["password"];

          if(DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))){
               if(password_verify($password, DB::query('SELECT password FROM users WHERE email=:email', array(':email'=>$email))[0]["password"])){
                    echo "logged in";

                    $cstrong = True;
                    $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                    $user_id = DB::query("SELECT id FROM users WHERE email=:email", array(":email"=>$email))[0]["id"];
                    DB::query("INSERT INTO login_tokens VALUES ('', :token, :user_id)", array(":token"=>sha1($token), ":user_id"=>$user_id));

                    setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, "/", NULL, NULL, TRUE);
                    setcookie("SNID_", "1", time() + 60 * 60 * 24 * 3, "/", NULL, NULL, TRUE);
                    header("Location: index.php");
               }
               else{
                    echo "incorrect password";
               }
          }
          else{
               echo("no user by that email please try again");
          }
     }
 ?>
