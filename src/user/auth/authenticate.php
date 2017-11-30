<?php

  // DATABASE CONNECTION CODE
  $db_host = getenv('DB_HOST');
  $db_name = getenv('DB_NAME');
  $db_user = getenv('DB_USER');
  $db_pass = getenv('DB_PASS');
  $db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8mb4',$db_user,$db_pass);
  // DATABASE CONNECTION CODE

  $id = 1;//$_GET['id'];
  $dr_users;

  $result = $db->query("SELECT dr_users,dr_permissions from gl_droffice where id = $id");
  $row = $result->fetch();
  $dr_users = $row['dr_users'];
  $dr_permissions = $row['dr_permissions'];


  $uname = $_GET["uname"];
  $upass = $_GET["upass"];


  $result = $db->query("select id from $dr_users where login='$uname' and passd='$upass'");
  if ($result->rowCount() == 1) {
      echo "authenticated";
  }

  else {
      echo "failed authentication";
  }

?>
