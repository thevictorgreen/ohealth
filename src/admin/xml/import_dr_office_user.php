<?php

  $con = mysql_connect("localhost","root","g0th@m"); //,false,MYSQL_CLIENT_SSL);
  mysql_select_db("OPENHEALTH",$con);

  $id = 1;//$_GET['id'];
  $dr_users;

  $result = mysql_query("SELECT dr_users,dr_permissions from gl_droffice where id = $id");
  $row = mysql_fetch_array($result);
  $dr_users = $row['dr_users'];
  $dr_permissions = $row['dr_permissions'];


  mysql_query("LOAD DATA LOCAL INFILE '../uploads/users.csv' INTO TABLE $dr_users FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' (first_name,last_name,login,passd,is_dr,dea,send_script,appr_req,email,lic_num)");
                                                                                                                                 //(first_name,last_name,login,passd,is_dr,dea,send_script,appr_req,email,lic_num)

  $result = mysql_query("SELECT id FROM $dr_users");
  while ($row = mysql_fetch_array($result)) {

         $u_id = $row["id"];
         $result2 = mysql_query("select u_id from $dr_permissions where u_id = $u_id");

         if (mysql_num_rows($result2) == 0) {
             mysql_query("INSERT INTO $dr_permissions (u_id,submitScript,submitScriptDirect,submitScriptQueOnly,viewScript,viewScriptQue,viewRefills,viewAdherence,viewPatients,viewCharts,viewFormulary,viewReports) VALUES ($u_id,'false','false','false','false','false','true','true','true','true','true','true')");
         }
  }


  unlink("../uploads/users.csv");
  echo "success";

?>
