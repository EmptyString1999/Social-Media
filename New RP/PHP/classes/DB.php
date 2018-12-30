<?php
     class DB{
          public static function connect(){
               $pdo = new PDO("mysql:host=127.0.0.1;dbname=new_rpl;charset=utf8", "root", "");
               $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
               return $pdo;
          }

          public static function query($query, $params = array()){
               $statement = self::connect()->prepare($query);
               $statement->execute($params);
               if (explode(" ", $query)[0] == "SELECT") {
               $data = $statement->fetchAll();
               if ($statement->rowCount() == 0) {
                    return null;
               } else {
                    return $data;
               }
          }
     }

     }
 ?>
