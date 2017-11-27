<?php

  $con = mysql_connect("localhost","root","g0th@m"); //,false,MYSQL_CLIENT_SSL);
  mysql_select_db("OPENHEALTH",$con);

  $id = 1;//$_GET['id'];
  $dr_users;

  $result = mysql_query("SELECT dr_users,dr_permissions from gl_droffice where id = $id");
  $row = mysql_fetch_array($result);
  $dr_users = $row['dr_users'];
  $dr_permissions = $row['dr_permissions'];

  $u_id                = $_GET['u_id'];
  $submitScript        = $_GET['submitScript'];
  $submitScriptDirect  = $_GET['submitScriptDirect'];
  $submitScriptQueOnly = $_GET['submitScriptQueOnly'];
  $viewScript          = $_GET['viewScript'];
  $viewScriptQue       = $_GET['viewScriptQue'];
  $viewRefills         = $_GET['viewRefills'];
  $viewAdherence       = $_GET['viewAdherence'];
  $viewPatients        = $_GET['viewPatients'];
  $viewCharts          = $_GET['viewCharts'];
  $viewFormulary       = $_GET['viewFormulary'];
  $viewReports         = $_GET['viewReports'];

  mysql_query("UPDATE $dr_permissions SET submitScript='$submitScript',submitScriptDirect='$submitScriptDirect',submitScriptQueOnly='$submitScriptQueOnly',viewScript='$viewScript',viewScriptQue='$viewScriptQue',viewRefills='$viewRefills',viewAdherence='$viewAdherence',viewPatients='$viewPatients',viewCharts='$viewCharts',viewFormulary='$viewFormulary',viewReports='$viewReports' WHERE u_id = $u_id");
  echo "success";

?>
