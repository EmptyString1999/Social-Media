<?php
     class Image
     {

          public static function img_upload($formname, $query, $params)
          {
               $image = base64_encode(file_get_contents($_FILES[$formname]['tmp_name']));

               $options = array('http'=>array(
                    'method'=>"POST",
                    'header'=>
                    "Content-type: application/x-www-form-urlencoded\n".
                    "Authorization: Bearer 85c5fd1f4ad1e5a6b63817a024e3094b040aebab",
                    'content'=> $image
               ));
               $context = stream_context_create($options);

               $imgurURL = "https://api.imgur.com/3/upload";

               if ($_FILES[$formname]['size'] < 10240000) {
                    $response = file_get_contents($imgurURL, false, $context);
                    $response = json_decode($response);

                    $preparams = array($formname => $response->data->link);

                    $params = $preparams + $params;

                    DB::query($query, $params);
               } else {
                    echo "file size to large, must be 10mb or less";
               }
          }
     }

 ?>
