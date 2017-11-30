<?php

  // DATABASE CONNECTION CODE
  $db_host = getenv('DB_HOST');
  $db_name = getenv('DB_NAME');
  $db_user = getenv('DB_USER');
  $db_pass = getenv('DB_PASS');
  $db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8mb4',$db_user,$db_pass);
  // DATABASE CONNECTION CODE

  $id = 1;//$_GET['id'];

  $result = $db->query("SELECT api_key from gl_droffice where id = $id");
  $row = $result->fetch();
  $api_key = $row['api_key'];

  echo $api_key;


?>
