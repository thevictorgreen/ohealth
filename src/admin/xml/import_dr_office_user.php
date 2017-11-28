<?php

  //require_once("/GDS/ohealth/admin/xml/settings.php");
  // DATABASE CONNECTION CODE
  $db_host = getenv('DB_HOST');
  $db_name = getenv('DB_NAME');
  $db_user = getenv('DB_USER');
  $db_pass = getenv('DB_PASS');
  $db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8mb4',$db_user,$db_pass,array(PDO::MYSQL_ATTR_LOCAL_INFILE => true));
  // DATABASE CONNECTION CODE

  $id = 1;//$_GET['id'];
  $dr_users;

  $result = $db->query("SELECT dr_users,dr_permissions from gl_droffice where id = $id");
  $row = $result->fetch();
  $dr_users = $row['dr_users'];
  $dr_permissions = $row['dr_permissions'];


  $db->query("LOAD DATA LOCAL INFILE '../uploads/users.csv' INTO TABLE $dr_users FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' (first_name,last_name,login,passd,is_dr,dea,send_script,appr_req,email,lic_num)");
                                                                                                                                 //(first_name,last_name,login,passd,is_dr,dea,send_script,appr_req,email,lic_num)

  $result = $db->query("SELECT id FROM $dr_users");
  while ( $row = $result->fetch() ) {

         $u_id = $row["id"];
         $result2 = $db->query("select u_id from $dr_permissions where u_id = $u_id");

         if ( $result2->rowCount() == 0 ) {
              $db->query("INSERT INTO $dr_permissions (u_id,submitScript,submitScriptDirect,submitScriptQueOnly,viewScript,viewScriptQue,viewRefills,viewAdherence,viewPatients,viewCharts,viewFormulary,viewReports) VALUES ($u_id,'false','false','false','false','false','true','true','true','true','true','true')");
         }
  }


  unlink("../uploads/users.csv");
  echo "success";

?>
