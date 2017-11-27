<?php

  $con = mysql_connect("localhost","root","g0th@m"); //,false,MYSQL_CLIENT_SSL);
  mysql_select_db("OPENHEALTH",$con);

  $id = 1;//$_GET['id'];
  $dr_users;

  $result = mysql_query("SELECT dr_users,dr_permissions from gl_droffice where id = $id");
  $row = mysql_fetch_array($result);
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


  $result = mysql_query("INSERT INTO $dr_users (first_name,last_name,login,passd,is_dr,dea,send_script,appr_req,email,lic_num) VALUES ('$first_name','$last_name','$login','$passd','$is_dr','$dea','$send_script','$appr_req','$email','$lic_num')");

  $result = mysql_query("SELECT id FROM $dr_users WHERE login = '$login'");
  $row = mysql_fetch_array($result);
  $u_id = $row['id'];

  mysql_query("INSERT INTO $dr_permissions (u_id,submitScript,submitScriptDirect,submitScriptQueOnly,viewScript,viewScriptQue,viewRefills,viewAdherence,viewPatients,viewCharts,viewFormulary,viewReports) VALUES ($u_id,'false','false','false','false','false','true','true','true','true','true','true')");

  echo "success";

?>
