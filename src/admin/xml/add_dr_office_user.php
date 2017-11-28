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

  $first_name  = $_GET['first_name'];
  $last_name   = $_GET['last_name'];
  $login       = $_GET['login'];
  $passd       = $_GET['passd'];
  $is_dr       = $_GET['is_dr'];
  $dea         = $_GET['dea'];
  $send_script = $_GET['send_script'];
  $appr_req    = $_GET['appr_req'];
  $email       = $_GET['email'];
  $lic_num     = $_GET['lic_num'];


  $result = $db->query("INSERT INTO $dr_users (first_name,last_name,login,passd,is_dr,dea,send_script,appr_req,email,lic_num) VALUES ('$first_name','$last_name','$login','$passd','$is_dr','$dea','$send_script','$appr_req','$email','$lic_num')");

  $result = $db->query("SELECT id FROM $dr_users WHERE login = '$login'");
  $row = $result->fetch();
  $u_id = $row['id'];

  $db->query("INSERT INTO $dr_permissions (u_id,submitScript,submitScriptDirect,submitScriptQueOnly,viewScript,viewScriptQue,viewRefills,viewAdherence,viewPatients,viewCharts,viewFormulary,viewReports) VALUES ($u_id,'false','false','false','false','false','true','true','true','true','true','true')");

  echo "success";

?>
