<?php

  //require_once("/GDS/ohealth/admin/xml/settings.php");
  $con = mysql_connect("localhost","root","g0th@m"); //,false,MYSQL_CLIENT_SSL);
  mysql_select_db("OPENHEALTH",$con);

  $id      = $_GET['id'];
  //$api_key = $_GET['api_key'];

  $result = mysql_query("SELECT * FROM gl_droffice WHERE id = $id");
  $row    = mysql_fetch_array($result);

  $dr_users               = $row['dr_users'];
  $dr_patients            = $row['dr_patients'];
  $dr_pharmacies          = $row['dr_pharmacies'];
  $dr_prescriptions       = $row['dr_prescriptions'];
  $dr_prescriptions_que   = $row['dr_prescriptions_que'];
  $dr_refills             = $row['dr_refills'];
  $dr_adherence           = $row['dr_adherence'];
  $dr_permissions         = $row['dr_permissions'];
  $dr_logs                = $row['dr_logs'];
  $dr_pmh                 = $row['dr_pmh'];
  $dr_diagnosis           = $row['dr_diagnosis'];
  $dr_allergies           = $row['dr_allergies'];
  $dr_prescriptions_other = $row['dr_prescriptions_other'];
  $dr_soap                = $row['dr_soap'];
  $dr_soap_diag           = $row['dr_soap_diag'];
  $dr_soap_script         = $row['dr_soap_script'];

  mysql_query("DROP table $dr_users");
  mysql_query("DROP table $dr_patients");
  mysql_query("DROP table $dr_pharmacies");
  mysql_query("DROP table $dr_prescriptions");
  mysql_query("DROP table $dr_prescriptions_que");
  mysql_query("DROP table $dr_refills");
  mysql_query("DROP table $dr_adherence");
  mysql_query("DROP table $dr_permissions");
  mysql_query("DROP table $dr_logs");
  mysql_query("DROP table $dr_pmh");
  mysql_query("DROP table $dr_diagnosis");
  mysql_query("DROP table $dr_allergies");
  mysql_query("DROP table $dr_prescriptions_other");
  mysql_query("DROP table $dr_soap");
  mysql_query("DROP table $dr_soap_diag");
  mysql_query("DROP table $dr_soap_script");

  mysql_query("DROP table gl_insurance");
  mysql_query("DROP table gl_ins_apprvQty");
  mysql_query("DROP table gl_ins_apprvSig");
  mysql_query("DROP table gl_pharmacy1");
  mysql_query("DROP table gl_sig");
  mysql_query("DELETE FROM gl_droffice WHERE id = $id");
  mysql_query("ALTER TABLE gl_droffice auto_increment = 1");

  echo "success";

?>
