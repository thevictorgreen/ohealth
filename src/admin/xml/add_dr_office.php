<?php

  //require_once("/GDS/ohealth/admin/xml/settings.php");

  // DATABASE CONNECTION CODE
  $db_host = getenv('DB_HOST');
  $db_name = getenv('DB_NAME');
  $db_user = getenv('DB_USER');
  $db_pass = getenv('DB_PASS');
  $db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8mb4',$db_user,$db_pass);
  // DATABASE CONNECTION CODE


  $sPattern = '/\s*/m';
  $sReplace = '';

  $name     = $_GET['name'];
  $addr     = $_GET['addr'];
  $city     = $_GET['city'];
  $state    = $_GET['state'];
  $zip      = $_GET['zip'];
  $phone    = $_GET['phone'];
  $o_id     = $_GET['o_id'];
  $api_key  = $_GET['api_key'];
  //$api_lic  = $_GET['api_lic'];
  //$base_url = $_GET['base_url'];

  $dr_users = "dr_office_" . $name . "_users";
  $dr_users = preg_replace($sPattern,$sReplace,$dr_users);

  $dr_patients = "dr_office_" . $name . "_patients";
  $dr_patients = preg_replace($sPattern,$sReplace,$dr_patients);

  $dr_pharmacies = "dr_office_" . $name . "_pharmacies";
  $dr_pharmacies = preg_replace($sPattern,$sReplace,$dr_pharmacies);

  $dr_prescriptions = "dr_office_" . $name . "_prescriptions";
  $dr_prescriptions = preg_replace($sPattern,$sReplace,$dr_prescriptions);

  $dr_prescriptions_que = "dr_office_" . $name . "_prescriptions_que";
  $dr_prescriptions_que = preg_replace($sPattern,$sReplace,$dr_prescriptions_que);

  $dr_refills = "dr_office_" . $name . "_refills";
  $dr_refills = preg_replace($sPattern,$sReplace,$dr_refills);

  $dr_adherence = "dr_office_" . $name . "_adherence";
  $dr_adherence = preg_replace($sPattern,$sReplace,$dr_adherence);

  $dr_permissions = "dr_office_" . $name . "_permissions";
  $dr_permissions = preg_replace($sPattern,$sReplace,$dr_permissions);

  $dr_logs = "dr_office_" . $name . "_logs";
  $dr_logs = preg_replace($sPattern,$sReplace,$dr_logs);

  $dr_pmh = "dr_office_" . $name . "_pmh";
  $dr_pmh = preg_replace($sPattern,$sReplace,$dr_pmh);

  $dr_diagnosis = "dr_office_" . $name . "_diagnosis";
  $dr_diagnosis = preg_replace($sPattern,$sReplace,$dr_diagnosis);

  $dr_allergies = "dr_office_" . $name . "_allergies";
  $dr_allergies = preg_replace($sPattern,$sReplace,$dr_allergies);

  $dr_prescriptions_other = "dr_office_" . $name . "_prescriptions_other";
  $dr_prescriptions_other = preg_replace($sPattern,$sReplace,$dr_prescriptions_other);

  $dr_soap = "dr_office_" . $name . "_soap";
  $dr_soap = preg_replace($sPattern,$sReplace,$dr_soap);

  $dr_soap_diag = "dr_office_" . $name . "_soap_diag";
  $dr_soap_diag = preg_replace($sPattern,$sReplace,$dr_soap_diag);

  $dr_soap_script = "dr_office_" . $name . "_soap_script";
  $dr_soap_script = preg_replace($sPattern,$sReplace,$dr_soap_script);

  try {
      $db->query("INSERT INTO gl_droffice (name,addr,city,state,zip,phone,o_id,api_key,dr_users,dr_patients,dr_pharmacies,dr_prescriptions,dr_prescriptions_que,dr_refills,dr_adherence,dr_permissions,dr_logs,dr_pmh,dr_diagnosis,dr_allergies,dr_prescriptions_other,dr_soap,dr_soap_diag,dr_soap_script) VALUES ('$name','$addr','$city','$state','$zip','$phone','$o_id','$api_key','$dr_users','$dr_patients','$dr_pharmacies','$dr_prescriptions','$dr_prescriptions_que','$dr_refills','$dr_adherence','$dr_permissions','$dr_logs','$dr_pmh','$dr_diagnosis','$dr_allergies','$dr_prescriptions_other','$dr_soap','$dr_soap_diag','$dr_soap_script')");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table $dr_users (id int not null auto_increment unique,first_name varchar(20),last_name varchar(20),login varchar(20),passd varchar(20),is_dr varchar(5),dea varchar(20),send_script varchar(5),appr_req varchar(5),email varchar(80),lic_num varchar(20))");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table $dr_patients (id int not null auto_increment unique,ins_id int,ins_mri blob,first_name blob,last_name blob,dob blob,addr blob,city blob,state blob,zip blob,phone blob,lat blob,lng blob,ins_grp blob,cell blob,email blob,insyst blob,ssec blob)");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table $dr_pharmacies (pharm_id int)");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table $dr_prescriptions (id int not null auto_increment unique,dea blob,dname blob,oname blob,daddr blob,csz blob,dphone blob,first_name blob,last_name blob,dob date,addr blob,city blob,state blob,zip blob,phone blob,ins_mri blob,ins_name blob,med blob,qty blob,refills blob,sub_perm blob,sig blob,pharm_id int(11),pt_id int(11),s_date DATE,s_time TIME,d_id int,doc_id int)");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table $dr_prescriptions_que (id int not null auto_increment unique,dea blob,dname blob,oname blob,daddr blob,csz blob,dphone blob,first_name blob,last_name blob,dob blob,addr blob,city blob,state blob,zip blob,phone blob,ins_mri blob,ins_name blob,med blob,qty blob,refills blob,sub_perm blob,sig blob,pharm_id int(11),pt_id int(11),d_id int(11),doc_id int(11))");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table $dr_refills (rf_id int not null auto_increment primary key,script_id int,refills int,auth_date date,auth_time time)");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table $dr_adherence (ad_id int not null auto_increment primary key,script_id int,contact_within int,expired varchar(5),t_supply int)");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table $dr_permissions (u_id int,submitScript varchar(5),submitScriptDirect varchar(5),submitScriptQueOnly varchar(5),viewScript varchar(5),viewScriptQue varchar(5),viewRefills varchar(5),viewAdherence varchar(5), viewPatients varchar(5), viewCharts varchar(5), viewFormulary varchar(5), viewReports varchar(5) )");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table $dr_logs (log_id int not null auto_increment primary key,l_date date,l_time time,u_id int,l_type varchar(30),l_action varchar(30),uname varchar(20),pt_id int,med_id varchar(60),ph_id int)");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table $dr_pmh (phm_id int not null auto_increment primary key, pt_id int, p1 blob, p2 blob, p3 blob, p4 blob, p5 blob, p6 blob, p7 blob, p8 blob)");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table $dr_diagnosis (diag_id int not null auto_increment primary key,pt_id int,code varchar(30),descr varchar(200),d_type varchar(7),diag_date date,u_id int)");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table $dr_allergies (al_id int not null auto_increment primary key,pt_id int,d_id int,d_name varchar(100))");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table $dr_prescriptions_other (os_id int not null auto_increment primary key,pt_id int,doc varchar(100),med varchar(200),sig varchar(100),os_date date)");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table $dr_soap (soap_id int not null auto_increment primary key,pt_id int,u_id int,ht blob,wt blob,bmi blob,bpmm blob,bphg blob,temp blob,pulse blob,resp blob,cc blob,s blob,o blob,p blob,soap_date date,soap_time time)");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table $dr_soap_diag (pt_id int,diag_id int)");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table $dr_soap_script (pt_id int,script_id int)");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table gl_insurance (ins_id int,name varchar(100),state varchar(2),type varchar(7),phone varchar(30),fax varchar(30),url varchar(100),pa_form varchar(100),has_formulary varchar(3))");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table gl_ins_apprvQty (id int not null auto_increment primary key,ins_id int,d_id int,qty int)");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table gl_ins_apprvSig (id int not null auto_increment primary key,ins_id int,d_id int,sig_id int)");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table gl_pharmacy1 (id int,name varchar(40),addr varchar(40),city varchar(40),state varchar(40),zip varchar(40),fax varchar(40),phone varchar(40),lat double,lng double)");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  try {
      $db->query("Create table gl_sig (id int not null auto_increment primary key,name varchar(140),expanded varchar(400))");
  } catch(PDOException $ex) {
      die( $ex-getMessage() );
  }

  echo "success";

?>
