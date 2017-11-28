<?php

  //require_once("/GDS/ohealth/admin/xml/settings.php");
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
  $uid  = $_GET['uid'];

  $db->query("DELETE FROM $dr_users WHERE id = $uid");
  $db->query("DELETE FROM $dr_permissions WHERE u_id = $uid");

  echo "success";

?>
