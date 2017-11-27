<?php

  $con = mysql_connect("localhost","root","g0th@m"); //,false,MYSQL_CLIENT_SSL);
  mysql_select_db("OPENHEALTH",$con);

  $id   = $_GET['id'];

  mysql_query("DELETE FROM gl_pharmacy1 WHERE id = $id");

  echo "success";

?>
