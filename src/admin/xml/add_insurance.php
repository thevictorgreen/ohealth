<?php

  $con = mysql_connect("localhost","root","g0th@m"); //,false,MYSQL_CLIENT_SSL);
  mysql_select_db("OPENHEALTH",$con);

  $ins_id   = $_GET['ins_id'];
  $name = $_GET['ins_name'];
  $state = $_GET['st_short'];

  mysql_query("INSERT INTO gl_insurance (ins_id,name,state) VALUES ($ins_id,'$name','$state')");

  echo "success";

?>
