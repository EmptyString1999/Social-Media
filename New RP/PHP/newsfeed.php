<?php
     include('includes/header.php');
     include('classes/Post.php');
     include('classes/Comment.php');

     if (!Login_class::isLoggedIn()) {
          header("Location: index.php");
     } else {
          $userid = Login_class::isLoggedIn();
     }

     $followingposts = DB::query('SELECT posts.id, posts.post_title, posts.likes, posts.post_date, posts.post_content, users.username FROM posts, followers, users WHERE posts.user_id = followers.user_id AND users.id = posts.user_id AND follower = :userid ORDER BY posts.post_date DESC', array(':userid' => $userid));

     if (isset($_GET['postid'])) {
          Post::likePost($_GET['postid'], $userid);
     }
     if (isset($_POST['comment'])) {
          Comment::createComment($_POST['commentbody'], $_GET['postid'], $userid);
     }


?>
     <div class="container-fluid text-center">
          <div class="row title">
               <div class="col-sm-3 text-left"></div>
               <div class="col-sm-6 text-left">
                    <h1 class="title">News Feed</h1>
               </div>
               <div class="col-sm-3"></div>
          </div>
          <hr>
          <div class="row content">
               <div class="col-sm-3 text-left"></div>
               <div class="col-sm-6 text-left">
                    <?php
                         foreach ($followingposts as $post) {
                              echo '<h1>Title - ' . $post['post_title'] . '</h1>' . '<h2>Author - ' .$post['username'] . '</h2><h4>' . $post['post_content'] . '</h4></br>' . $post['post_date'] . '</br>';
                              echo "<form action='newsfeed.php?postid=".$post['id']."' method='post'>";
                                   if(!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$post['id'], ':userid'=>$userid))){
                                        echo "<input type='submit' name='like' value='Like'>";
                                   } else {
                                        echo "<input type='submit' name='like' value='Unlike'>";
                                   }
                                   echo "<span>".$post['likes']." likes</span></form>
                                   <form action=newsfeed.php?postid=".$post['id']." method='post'>
                                        <textarea rows='4' cols='55' name='commentbody'/></textarea></br>
                                        <input type='submit' name='comment' value='Comment'>
                                   </form>
                                   <div class='row'>
                                        <div class='col-sm-6 text-left'>
                                        <h4>Comments</h4>
                                   ";

                                        Comment::displayComments($post['id']);
                                        echo "
                                        <div class='col-sm-6'></div>
                                        </div>
                                   </div>
                                   <hr>";
                         }
                    ?>
               </div>
               <div class="col-sm-3"></div>
          </div>

     </div>
     <?php include("includes/footer.php")?>
