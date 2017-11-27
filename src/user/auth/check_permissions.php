<?php

  $con = mysql_connect("localhost","root","g0th@m"); //,false,MYSQL_CLIENT_SSL);
  mysql_select_db("OPENHEALTH",$con);


  $result = mysql_query("SELECT dr_users,dr_permissions from gl_droffice where id = 1");
  $row = mysql_fetch_array($result);
  $dr_users = $row['dr_users'];
  $dr_permissions = $row['dr_permissions'];

  $uname      = $_GET["uname"];
  $permission = $_GET["perm"];

  $result = mysql_query("select id from $dr_users where login = '$uname' ");
  $row = mysql_fetch_array($result);
  $id = $row['id'];
  $md_id = $id;

  //header('Content-type: application/json');



 $result = mysql_query("select * from $dr_permissions where u_id = $id");
 $row = mysql_fetch_array($result);

 $submitScript  = $row['submitScript'];
 $viewScript    = $row['viewScript'];
 $viewScriptQue = $row['viewScriptQue'];
 $viewRefills   = $row['viewRefills'];
 $viewAdherence = $row['viewAdherence'];
 $viewPatients  = $row['viewPatients'];
 $viewCharts    = $row['viewCharts'];
 $viewFormulary = $row['viewFormulary'];
 $viewReports   = $row['viewReports'];

 $result = mysql_query("select is_dr from $dr_users where id = $id");
 $row = mysql_fetch_array($result);
 $is_dr = $row['is_dr'];

 if ($permission == "is_dr") {
     echo $is_dr;
 }

 if ($permission == "md_id") {
     echo $md_id;
 }

 if ($permission == "submitScript") {
     echo $submitScript;
 }

 if ($permission == "viewScript") {
     echo $viewScript;
 }

 if ($permission == "viewScriptQue") {
     echo $viewScriptQue;
 }

 if ($permission == "viewRefills") {
     echo $viewRefills;
 }

 if ($permission == "viewAdherence") {
     echo $viewAdherence;
 }

 if ($permission == "viewPatients") {
     echo $viewPatients;
 }

 if ($permission == "viewCharts") {
     echo $viewCharts;
 }

 if ($permission == "viewFormulary") {
     echo $viewFormulary;
 }

 if ($permission == "viewReports") {
     echo $viewReports;
 }



        //echo       "{";
        //echo           "\"submitScript\":\"$submitScript\",";
        //echo           "\"viewScript\":\"$viewScript\",";
        //echo           "\"viewScriptQue\":\"$viewScriptQue\",";
        //echo           "\"viewAdherence\":\"$viewAdherence\",";
        //echo           "\"viewPatients\":\"$viewPatients\",";
        //echo           "\"viewCharts\":\"$viewCharts\",";
        //echo           "\"viewFormulary\":\"$viewFormulary\"";
        //echo       "}";


?>
