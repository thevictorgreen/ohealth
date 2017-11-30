<?php

  // DATABASE CONNECTION CODE
  $db_host = getenv('DB_HOST');
  $db_name = getenv('DB_NAME');
  $db_user = getenv('DB_USER');
  $db_pass = getenv('DB_PASS');
  $db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8mb4',$db_user,$db_pass);
  // DATABASE CONNECTION CODE


  $result = $db->query("SELECT dr_users,dr_permissions from gl_droffice where id = 1");
  $row = $result->fetch();
  $dr_users = $row['dr_users'];
  $dr_permissions = $row['dr_permissions'];

  $uname      = $_GET["uname"];
  $permission = $_GET["perm"];

  $result = $db->query("select id from $dr_users where login = '$uname' ");
  $row = $result->fetch();
  $id = $row['id'];
  $md_id = $id;

  //header('Content-type: application/json');



 $result = $db->query("select * from $dr_permissions where u_id = $id");
 $row = $result-fetch();

 $submitScript  = $row['submitScript'];
 $viewScript    = $row['viewScript'];
 $viewScriptQue = $row['viewScriptQue'];
 $viewRefills   = $row['viewRefills'];
 $viewAdherence = $row['viewAdherence'];
 $viewPatients  = $row['viewPatients'];
 $viewCharts    = $row['viewCharts'];
 $viewFormulary = $row['viewFormulary'];
 $viewReports   = $row['viewReports'];

 $result = $db->query("select is_dr from $dr_users where id = $id");
 $row = $result-fetch();
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

?>
