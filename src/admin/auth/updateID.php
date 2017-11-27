<?php

  $con = mysql_connect("localhost","root","g0th@m");
  mysql_select_db("FireDrillDB",$con);

  $result = mysql_query("select nUserIdn from fd_user");

  while ( $row = mysql_fetch_array($result) ) {

          //print $row["nUserIdn"] . "\n";
          $nUserIdn = $row["nUserIdn"];

          mysql_query("update fd_user set sUserID = '$nUserIdn' where nUserIdn = '$nUserIdn' ");

  }


  print "Updated";


?>
