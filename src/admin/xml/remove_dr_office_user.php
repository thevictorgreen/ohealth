<?php

  $con = mysql_connect("localhost","root","g0th@m"); //,false,MYSQL_CLIENT_SSL);
  mysql_select_db("OPENHEALTH",$con);

  $id = 1;//$_GET['id'];
  $dr_users;

  $result = mysql_query("SELECT dr_users,dr_permissions from gl_droffice where id = $id");
  $row = mysql_fetch_array($result);
  $dr_users = $row['dr_users'];
  $dr_permissions = $row['dr_permissions'];
  $uid  = $_GET['uid'];

  mysql_query("DELETE FROM $dr_users WHERE id = $uid");
  mysql_query("DELETE FROM $dr_permissions WHERE u_id = $uid");

  echo "success";

?>
