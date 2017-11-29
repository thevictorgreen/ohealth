<?php

  // DATABASE CONNECTION CODE
  $db_host = getenv('DB_HOST');
  $db_name = getenv('DB_NAME');
  $db_user = getenv('DB_USER');
  $db_pass = getenv('DB_PASS');
  $db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8mb4',$db_user,$db_pass);
  // DATABASE CONNECTION CODE

  $id = $_GET['id'];
  $fax = $_GET['fax'];

  $db->query("UPDATE gl_pharmacy1 SET fax='$fax' WHERE id = $id");
  echo "success";

?>
