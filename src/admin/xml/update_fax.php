<?php

  $con = mysql_connect("localhost","root","g0th@m"); //,false,MYSQL_CLIENT_SSL);
  mysql_select_db("OPENHEALTH",$con);

  $id = $_GET['id'];
  $fax = $_GET['fax'];

  mysql_query("UPDATE gl_pharmacy1 SET fax='$fax' WHERE id = $id");
  echo "success";

?>
