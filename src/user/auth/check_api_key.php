<?php

  $con = mysql_connect("localhost","root","g0th@m"); //,false,MYSQL_CLIENT_SSL);
  mysql_select_db("OPENHEALTH",$con);

  $id = 1;//$_GET['id'];

  $result = mysql_query("SELECT api_key from gl_droffice where id = $id");
  $row = mysql_fetch_array($result);
  $api_key = $row['api_key'];

  echo $api_key;


?>
