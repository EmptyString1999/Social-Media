<?php
     class Comment{
          public static function createComment($commentBody, $postid, $userid){
               if (!DB::query('SELECT id FROM posts WHERE id=:postid', array(':postid' => $postid))) {
                    echo 'invalid post ID';
               } else {
                    DB::query('INSERT INTO comments VALUES (\'\', :comment, :userid, NOW(), :postid)', array(':comment'=>$commentBody, ':userid'=>$userid, ':postid' => $postid));
               }
          }

          public static function displayComments($postid)
          {
               $comments = DB::query('SELECT comments.comment, comments.comment_date, users.username FROM comments, users WHERE comments.post_id = :postid AND comments.user_id = users.id', array(':postid' => $postid));
               foreach ($comments as $comment) {
                    echo  "</h5>Comment - </h5>".$comment['comment'] . " </br>" . "</h5>Username - </h5>". $comment['username'] . " </br>" . "</h5>Date - </h5>". $comment['comment_date'] . "<hr>";
               }
          }
     }
?>
