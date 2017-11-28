<?php

  //require_once("/GDS/ohealth/admin/xml/settings.php");
  // DATABASE CONNECTION CODE
  $db_host = getenv('DB_HOST');
  $db_name = getenv('DB_NAME');
  $db_user = getenv('DB_USER');
  $db_pass = getenv('DB_PASS');
  $db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8mb4',$db_user,$db_pass);
  // DATABASE CONNECTION CODE

  $id = 1;//$_GET['id'];
  $dr_users;

  $result = $db->query("SELECT dr_users,dr_permissions from gl_droffice where id = $id");
  $row = $result->fetch();
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

  $db->query("UPDATE $dr_permissions SET submitScript='$submitScript',submitScriptDirect='$submitScriptDirect',submitScriptQueOnly='$submitScriptQueOnly',viewScript='$viewScript',viewScriptQue='$viewScriptQue',viewRefills='$viewRefills',viewAdherence='$viewAdherence',viewPatients='$viewPatients',viewCharts='$viewCharts',viewFormulary='$viewFormulary',viewReports='$viewReports' WHERE u_id = $u_id");
  echo "success";

?>
