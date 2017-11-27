<?php

  $con = mysql_connect("localhost","root","g0th@m"); //,false,MYSQL_CLIENT_SSL);
  mysql_select_db("OPENHEALTH",$con);

  $id    = $_GET['id'];
  $name  = $_GET['name'];
  $addr  = $_GET['addr'];
  $city  = $_GET['city'];
  $state = $_GET['state'];
  $phone = $_GET['phone'];
  $lat   = $_GET['lat'];
  $lon   = $_GET['lon'];

  mysql_query("INSERT INTO gl_pharmacy1 (id,name,addr,city,state,phone,lat,lng) VALUES ($id,'$name','$addr','$city','$state','$phone','$lat','$lon')");

  echo "success";

?>
