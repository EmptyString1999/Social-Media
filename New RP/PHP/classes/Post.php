<?php
     class Post{
          public static function createPost($posttitle, $postcont, $loggedinuserid, $profileuserid){
               if ($loggedinuserid == $profileuserid) {
                    if (strlen($postcont)>1) {

                         if(count(Notify::createNotify($postcont)) != 0){
                              foreach(Notify::createNotify($postcont) as $key => $n){
                                   $s = $loggedinuserid;
                                   $r = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$key))[0]['id'];
                                   if($r != 0){
                                        DB::query('INSERT INTO notifications VALUES (\'\', :type, :receiver, :sender, :extra)', array(':type'=>$n["type"], ':receiver'=>$r, ':sender'=>$s, ':extra'=>$n["extra"]));
                                   }
                              }
                         }

                         DB::query('INSERT INTO posts VALUES (\'\', :posttitle, :postcont, NOW(), :userid, 0, \'\')', array(':posttitle'=>$posttitle, ':postcont' => $postcont, ':userid'=>$profileuserid));
                    }
               } else {
                    echo "incorrect user";
               }
          }

          public static function createImgPost($posttitle, $postcont, $loggedinuserid, $profileuserid){
               if ($loggedinuserid == $profileuserid) {

                    if(count(Notify::createNotify($postcont)) != 0){
                         foreach(Notify::createNotify($postcont) as $key => $n){
                              $s = $loggedinuserid;
                              $r = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$key))[0]['id'];
                              if($r != 0){
                                   DB::query('INSERT INTO notifications VALUES (\'\', :type, :receiver, :sender, :extra)', array(':type'=>$n["type"], ':receiver'=>$r, ':sender'=>$s, ':extra'=>$n["extra"]));
                              }
                         }
                    }

                    DB::query('INSERT INTO posts VALUES (\'\', :posttitle, :postcont, NOW(), :userid, 0, \'\')', array(':posttitle'=>$posttitle, ':postcont' => $postcont, ':userid'=>$profileuserid));
                    $postid = DB::query('SELECT * FROM `posts` WHERE user_id=:userid ORDER BY id DESC LIMIT 1', array(':userid' => $loggedinuserid))[0]['id'];
                    return $postid;
               } else {
                    echo "incorrect user";
               }
          }

          public static function likePost($postid, $likerid){
               if (!DB::query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postid, ':userid'=>$likerid))) {
                    DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid',array(':postid'=>$postid));
                    DB::query('INSERT INTO post_likes VALUES (\'\', :postid, :userid)', array(':postid'=>$postid, 'userid'=>$likerid));
                    Notify::createNotify("", $postid);
               } else {
                    DB::query('UPDATE posts SET likes=likes-1 WHERE id=:postid',array(':postid'=>$postid));
                    DB::query('DELETE FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postid, ':userid'=>$likerid));
               }
          }

          public static function link_add($text){

               $text = explode(" ", $text);
               $newstring = "";

               foreach ($text as $word) {
                    if (substr($word, 0, 1) == "@") {
                         $newstring .= "<a href='profile.php?username=".substr($word, 1)."'>" .$word."</a> ";
                    } else {
                         $newstring .= $word." ";
                    }
               }
               return $newstring;
          }

          public static function displayPost($userid, $username, $loggedinuserid){
               $dbposts = DB::query('SELECT * FROM posts WHERE user_id=:userid ORDER BY id DESC', array(':userid'=>$userid));
               $posts = "";

               if (is_array($dbposts)) {
                    foreach ($dbposts as $p) {

                         if(!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$loggedinuserid))){
                              $posts .= $p['post_title']."</br>". "<img src='" .$p['post_img']."'>"."</br>".self::link_add($p['post_content'])."</br>
                              <form action='profile.php?username=$username&postid=".$p['id']."' method='post'>
                                   <input type='submit' name='like' value='Like'>
                                   <span>".$p['likes']." likes</span>
                                   ";
                                   if($userid == $loggedinuserid){
                                        $posts .= "<input type='submit' name='deletepost' value='Delete'/>";
                                   }
                                   $posts .= "
                                   </form><hr>";
                         } else {
                              $posts .= $p['post_title']."</br>"."<img src='" .$p['post_img']."'>".$p['post_content']."</br>
                              <form action='profile.php?username=$username&postid=".$p['id']."' method='post'>
                                   <input type='submit' name='like' value='Unlike'>
                                   <span>".$p['likes']." likes</span>
                                   ";
                                   if($userid == $loggedinuserid){
                                        $posts .= "<input type='submit' name='deletepost' value='Delete'/>";
                                   }
                                   $posts .= "
                                   </form><hr>";
                         }
                    }
               }
               return $posts;
          }
     }
?>
