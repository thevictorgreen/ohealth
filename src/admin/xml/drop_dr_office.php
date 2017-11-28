<?php

  //require_once("/GDS/ohealth/admin/xml/settings.php");
  // DATABASE CONNECTION CODE
  $db_host = getenv('DB_HOST');
  $db_name = getenv('DB_NAME');
  $db_user = getenv('DB_USER');
  $db_pass = getenv('DB_PASS');
  $db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8mb4',$db_user,$db_pass);
  // DATABASE CONNECTION CODE;

  $id      = $_GET['id'];
  //$api_key = $_GET['api_key'];

  $result = $db->query("SELECT * FROM gl_droffice WHERE id = $id");
  $row    = $result->fetch();

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

  $db->query("DROP table $dr_users");
  $db->query("DROP table $dr_patients");
  $db->query("DROP table $dr_pharmacies");
  $db->query("DROP table $dr_prescriptions");
  $db->query("DROP table $dr_prescriptions_que");
  $db->query("DROP table $dr_refills");
  $db->query("DROP table $dr_adherence");
  $db->query("DROP table $dr_permissions");
  $db->query("DROP table $dr_logs");
  $db->query("DROP table $dr_pmh");
  $db->query("DROP table $dr_diagnosis");
  $db->query("DROP table $dr_allergies");
  $db->query("DROP table $dr_prescriptions_other");
  $db->query("DROP table $dr_soap");
  $db->query("DROP table $dr_soap_diag");
  $db->query("DROP table $dr_soap_script");

  $db->query("DROP table gl_insurance");
  $db->query("DROP table gl_ins_apprvQty");
  $db->query("DROP table gl_ins_apprvSig");
  $db->query("DROP table gl_pharmacy1");
  $db->query("DROP table gl_sig");
  $db->query("DELETE FROM gl_droffice WHERE id = $id");
  $db->query("ALTER TABLE gl_droffice auto_increment = 1");

  echo "success";

?>
