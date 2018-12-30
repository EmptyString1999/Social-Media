<?php
     include('includes/header.php');
     $username = "";
     if (Login_class::isLoggedIn()) {
          $userid = Login_class::isLoggedIn();
     }
     else{
          die("not logged in");
     }
?>
<div class="container-fluid text-center">
     <div class="row title">
          <div class="col-sm-3 text-left"></div>
          <div class="col-sm-6 text-left">
               <h1 class="title">Notifications</h1>
          </div>
          <div class="col-sm-3"></div>
     </div>
     <hr>
     <div class="row content">
          <div class="col-sm-3 text-left"></div>
          <div class="col-sm-6 text-left">
               <?php
                    if(DB::query('SELECT * FROM notifications WHERE receiver=:userid', array(':userid'=>$userid))){
                         $notifications = DB::query('SELECT * FROM notifications WHERE receiver=:userid', array(':userid'=>$userid));

                         foreach ($notifications as $n) {
                              if($n['type'] == 1){
                                   $senderName = DB::query('SELECT username FROM users WHERE id=:senderid', array(':senderid'=>$n['sender']))[0]['username'];
                                   if($n['extra'] != ""){
                                        $extra = json_decode($n['extra']);
                                        echo $senderName . " mentioned you in a post - ". $extra->postcont ." <hr>";
                                   } else {
                                        echo $senderName . " mentioned you in a post - <hr>";
                                   }

                              }
                         }
                    }
               ?>
          </div>
          <div class="col-sm-3"></div>
     </div>

</div>
