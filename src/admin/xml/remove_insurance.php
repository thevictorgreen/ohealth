<?php

  $con = mysql_connect("localhost","root","g0th@m"); //,false,MYSQL_CLIENT_SSL);
  mysql_select_db("OPENHEALTH",$con);

  $ins_id   = $_GET['ins_id'];

  mysql_query("DELETE FROM gl_insurance WHERE ins_id = $ins_id");

  echo "success";

?>
