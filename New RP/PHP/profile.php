<?php
     include_once("classes/DB.php");
     include('login.php');
     include('classes/Login_class.php');
     include('classes/Post.php');
     include('classes/Image.php');
     include('classes/Notify.php');

     $username = "";
     $isFollowing = false;
     $verified = false;
     $usersProfile = false;
     if (Login_class::isLoggedIn()) {
          echo "logged in";
          echo Login_class::isLoggedIn();
     }
     else{
          echo "not logged in";
     }

     if (isset($_GET['username'])) {
          if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))) {

               $username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
               $userid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
               $followerid = Login_class::isLoggedIn();
               $loggedinusername = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>$followerid))[0]['username'];


               if ($userid == $followerid) {
                    $usersProfile = true;
               }

               if (isset($_POST['follow'])) {
                    if (!DB::query('SELECT follower FROM followers WHERE user_id=:userid AND follower=:followerid', array(':userid' => $userid, ':followerid' => $followerid))) {
                         DB::query('INSERT INTO followers VALUES (\'\', :userid, :followerid)', array(':userid' => $userid, ':followerid' => $followerid));
                    } else {
                         echo "already following";
                    }
                    $isFollowing = true;
               }

               if (isset($_POST['unfollow'])) {

                    if (DB::query('SELECT follower FROM followers WHERE user_id=:userid AND follower=:followerid', array(':userid' => $userid, ':followerid' => $followerid))) {
                         DB::query('DELETE FROM followers WHERE user_id=:userid AND follower=:followerid', array(':userid' => $userid, ':followerid' => $followerid));
                    }
                    $isFollowing = false;
               }

               if (DB::query('SELECT follower FROM followers WHERE user_id=:userid AND follower=:followerid', array(':userid' => $userid, ':followerid' => $followerid))) {
                    $isFollowing = true;
               }
               $loggedinuserid = Login_class::isLoggedIn();
               if (isset($_POST['post'])) {
                    if ($_FILES['post_img']['size'] > 0) {
                         $postid = Post::createImgPost($_POST['posttitle'], $_POST['postcont'], $loggedinuserid, $userid);
                         Image::img_upload('post_img',"UPDATE posts SET post_img=:post_img WHERE id=:postid", array(':postid' => $postid));
                    } else {
                         Post::createPost($_POST['posttitle'], $_POST['postcont'], $loggedinuserid, $userid);
                    }

               }

               if (isset($_GET['postid']) && !isset($_POST['deletepost'])) {
                    Post::likePost($_GET['postid'], $followerid);
               }

               if (isset($_POST['deletepost'])) {
                    if (DB::query('SELECT id FROM posts WHERE id=:postid AND user_id=:userid', array(':postid' => $_GET['postid'], ':userid' => $followerid))) {
                         DB::query('DELETE FROM posts WHERE id=:postid AND user_id=:userid', array(':postid' => $_GET['postid'], ':userid' => $followerid));
                         DB::query('DELETE FROM post_likes WHERE post_id=:postid', array(':postid' => $_GET['postid']));
                    }
               }

               $posts = Post::displayPost($userid, $username, $followerid);
          } else {
               echo "no user by that name";
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
                                   <a href="profile.php?username=<?php echo $loggedinusername?>"><input class="btn" type="button" value="My Profile"></a>
                                   <a href="logout.php"><input class="btn" type="button" name="logout-btn" value="logout"></a>
                              </form>
                              <?php } ?>
                         </ul>
                    </div>
               </div>
          </nav>

          <?php if(Login_class::isLoggedIn()){?>

          <div class="container-fluid text-center">
               <div class="row">
                    <div class="col-sm-1 text-left"></div>
                    <div class="col-sm-10 text-left">
                         <?php
                         if($loggedinuserid == $userid){
                              if (DB::query('SELECT coverimg FROM users WHERE id=:userid', array(':userid' => $userid))[0]['coverimg']) {
                                   $coverimagesrc = DB::query('SELECT coverimg FROM users WHERE id=:userid', array(':userid' => $userid));
                                   if (!empty($coverimagesrc) || !is_null($coverimagesrc)) {
                                        ?> <a href="my_account.php" style=""><img src="<?php echo $coverimagesrc[0]['coverimg']; ?>" alt="cover photo" style="width: 100%; max-height: 40vh;"></a> <?php
                                   } else {
                                        ?> <a href="my_account.php" style=""><img src="https://i.imgur.com/QNLgVmw.jpg" alt=" defualt cover photo" class="img-thumbnail" style="min-width: 100%; max-height: 40vh;"></a> <?php }
                              }else{ ?> <a href="my_account.php" style=""><img src="https://i.imgur.com/QNLgVmw.jpg" alt=" defualt cover photo" class="img-thumbnail" style="min-width: 100vw; max-height: 40vh;"></a> <?php }
                         } else {
                              if (DB::query('SELECT coverimg FROM users WHERE id=:userid', array(':userid' => $userid))[0]['coverimg']) {
                                   $coverimagesrc = DB::query('SELECT coverimg FROM users WHERE id=:userid', array(':userid' => $userid));
                                   if (!empty($coverimagesrc) || !is_null($coverimagesrc)) {
                                        ?> <img src="<?php echo $coverimagesrc[0]['coverimg']; ?>" alt="cover photo" style="width: 100%; max-height: 40vh;"> <?php
                                   } else {
                                        ?> <img src="https://i.imgur.com/QNLgVmw.jpg" alt=" defualt cover photo" class="img-thumbnail" style="min-width: 100%; max-height: 40vh;"> <?php }
                              }else{ ?> <img src="https://i.imgur.com/QNLgVmw.jpg" alt=" defualt cover photo" class="img-thumbnail" style="min-width: 100vw; max-height: 40vh;"> <?php }
                         }?>

                    </div>
                    <div class="col-sm-1 text-left"></div>
               </div>
               <div class="row">
                    <div class="col-sm-1 text-left"></div>
                    <div class="col-sm-2 text-left">
                         <?php
                         if($loggedinuserid == $userid){
                              if (DB::query('SELECT profileimg FROM users WHERE id=:userid', array(':userid' => $userid))[0]['profileimg']) {
                                   $profileimagesrc = DB::query('SELECT profileimg FROM users WHERE id=:userid', array(':userid' => $userid));
                                   if (!empty($profileimagesrc) || !is_null($profileimagesrc)) {
                                        ?> <a href="my_account.php" style=""><img src="<?php echo $profileimagesrc[0]['profileimg']; ?>" alt="profile picture" class="img-thumbnail" style="position: absolute; width: 50%; bottom: -10vh; left: 2vw;"></a> <?php
                                   } else {
                                        ?> <a href="my_account.php" style=""><a href="my_account.php" style=""><img src="https://i.imgur.com/QNLgVmw.jpg" alt=" defualt profile picture" class="img-thumbnail" style="position: absolute; width: 50%; bottom: -10vh; left: 2vw;"></a> <?php }
                              }else{ ?> <a href="my_account.php" style=""><img src="https://i.imgur.com/QNLgVmw.jpg" alt=" defualt profile picture" class="img-thumbnail" style="position: absolute; width: 50%; bottom: -10vh; left: 2vw;"> </a><?php }
                         } else {
                              if (DB::query('SELECT profileimg FROM users WHERE id=:userid', array(':userid' => $userid))[0]['profileimg']) {
                                   $profileimagesrc = DB::query('SELECT profileimg FROM users WHERE id=:userid', array(':userid' => $userid));
                                   if (!empty($profileimagesrc) || !is_null($profileimagesrc)) {
                                        ?> <img src="<?php echo $profileimagesrc[0]['profileimg']; ?>" alt="profile picture" class="img-thumbnail" style="position: absolute; width: 50%; bottom: 2vh; left: 2vw;"> <?php
                                   } else {
                                        ?> <img src="https://i.imgur.com/QNLgVmw.jpg" alt=" defualt profile picture" class="img-thumbnail" style="position: absolute; width: 50%; bottom: 2vh; left: 2vw;"> <?php }
                              }else{ ?> <img src="https://i.imgur.com/QNLgVmw.jpg" alt=" defualt profile picture" class="img-thumbnail" style="position: absolute; width: 50%; bottom: 2vh; left: 2vw;"> <?php }
                         } ?>


                    </div>
                    <div class="col-sm-6 text-left">
                         <h1 class="title">Social Media</h1>
                         <hr>
                    </div>
                    <div class="col-sm-1"></div>
               </div>
               <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8 text-left content-center">
                         <h1><?php echo $username?>'s Profile </h1>
                         <?php if (!$usersProfile) {?>
                         <form action="profile.php?username<?php echo "=".$username;?>" method="post">
                              <?php
                                   if(!$isFollowing){
                              ?>
                                   <input type="submit" name="follow" value="Follow">
                              <?php
                              }else{
                              ?>
                                   <input type="submit" name="unfollow" value="Unfollow">
                              <?php
                              }
                              ?>
                         </form>
                    <?php } ?>
                    </div>
                    <div class="col-sm-2"></div>
               </div>
               </br>
               <hr>
               </br>
               <div class="row ">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10 text-left content-center">
                         <h1>Posts</h1>
                         <?php if ($loggedinuserid == $userid) {?>
                              <form action="profile.php?username<?php echo "=".$username;?>" method="post" enctype="multipart/form-data">
                                   <span class="label label-default">Title</span></br>
                                   <input type="textarea" rows="1" name="posttitle"/></br>
                                   <span class="label label-default">Content</span></br>
                                   <span>Upload an image:</span></br>
                                   <input type="file" name="post_img"/></br>
                                   <textarea rows="8" cols="64" name="postcont"/></textarea></br>
                                   <input id="send-data" type="submit" name="post" value="Post">
                              </form>
                         <?php }?>
                         <div class="posts">
                              <?php echo $posts;?>
                         </div>
                    </div>
                    <div class="col-sm-1"></div>
               </div>
          </div>
     <?php } else { ?>
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
                         <h1>Please Login to veiw profiles</h1>
                    </div>
                    <div class="col-sm-2"></div>
               </div>
          </div>
     <?php }?>
          <footer class="container-fluid text-center">
               <p>A Website Made By Ethan Price</p>
          </footer>
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
          <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
          <script src="//cdn.quilljs.com/1.3.6/quill.core.js"></script>
          <!--<script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
          <script type="text/javascript">
          var toolbarOptions = [
               ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
               ['blockquote', 'code-block'],

               [{ 'header': 1 }, { 'header': 2 }],               // custom button values
               [{ 'list': 'ordered'}, { 'list': 'bullet' }],
               [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
               [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
               [{ 'direction': 'rtl' }],                         // text direction

               [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
               [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

               [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
               [{ 'font': [] }],
               [{ 'align': [] }],

               ['clean']                                         // remove formatting button
          ];

          var quill = new Quill('#editor', {
               debug: 'info',
               modules: {
                    toolbar: toolbarOptions
               },
               placeholder: 'Compose something epic...',
               readOnly: false,
               theme: 'snow'
          });

          $('#send-data').click(send_data(){
               var delta = quill.getContents();
               $.ajax({
                   type: 'POST',
                   data: {
                       delta;
                   },
                   success: function(msg){
                       alert('wow' + msg);
                   }
               });
          });

     </script>-->
     </body>

</html>
