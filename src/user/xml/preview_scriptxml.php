<?php

  header('Content-type: application/xml');
  //require_once("/logiscript/cert/settings.php");

  //$con = mysql_connect("localhost","root","g0th@m");
  //mysql_select_db("ACBHS",$con);

  $conn=mysql_connect("localhost","root","g0th@m");
  mysql_select_db("OPENHEALTH",$conn);


  //GET APPROPRIATE TABLE(S)
  $result = mysql_query("SELECT dr_patients,dr_users,api_key from gl_droffice where id = 1");
  $row = mysql_fetch_array($result);
  $dr_patients = $row['dr_patients'];
  $dr_users = $row['dr_users'];
  $api_key = $row['api_key'];

  //GET TDE KEY FROM TDE SERVER
  $service_url = 'http://api.firstmedisource.com/call.php/gettdekey/' . $api_key . '?user_key=' . $api_key;
  $curl = curl_init($service_url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $curl_response = curl_exec($curl);

  if ($curl_response === false) {
      $info = curl_getinfo($curl);
      curl_close($curl);
      die('error occured during curl exec. Additioanl info: ' . var_export($info));
  }

  curl_close($curl);
  $decoded = json_decode($curl_response,true);

  if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
      die('error occured: ' . $decoded->response->errormessage);
  }

  $tde_key = $decoded[0]["tde_key"];

  $md_id      = $_GET['md_id'];
  $pt_id      = $_GET['pt_id'];
  $medication = $_GET['med'];
  $qty        = $_GET['qty'];
  $sig        = $_GET['sig'];
  $refills    = $_GET['refills'];
  $sub_perm   = $_GET['sub_perm'];
  $ph_id      = $_GET['ph_id'];

  $result = mysql_query("select first_name,last_name from $dr_users where id = $md_id");
  $row = mysql_fetch_array($result);
  $first_name = $row['first_name'];
  $last_name = $row['last_name'];
  $md = $first_name . " " . $last_name;

  $result = mysql_query("select AES_DECRYPT(first_name,'$tde_key') as first,AES_DECRYPT(last_name,'$tde_key') as last, AES_DECRYPT(dob,'$tde_key') as dob from $dr_patients where id = $pt_id");
  $row = mysql_fetch_array($result);
  $first = $row['first'];
  $last = $row['last'];
  $dob = $row['dob'];
  $patient = $first . " " . $last . " " . $dob;


  $result = mysql_query("SELECT name,addr,city,state,phone FROM gl_pharmacy1 where id = $ph_id");
  $row = mysql_fetch_array($result);

  $name  = $row['name'];
  $addr  = $row['addr'];
  $city  = $row['city'];
  $state = $row['state'];
  $phone = $row['phone'];
  $pharmacy = $name . " " . $addr . " " . $city . "," . $state . " " . $phone;

  echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
  echo "<scripts>";


        echo "<script>";

        echo "<md>"          . $md          . "</md>";
        echo "<patient>"     . $patient     . "</patient>";
        echo "<medication>"  . $medication  . "</medication>";
        echo "<quantity>"    . $qty         . "</quantity>";
        echo "<sig>"         . $sig         . "</sig>";
        echo "<refills>"     . $refills     . "</refills>";
        echo "<sub_perm>"    . $sub_perm    . "</sub_perm>";
        echo "<pharmacy>"    . $pharmacy    . "</pharmacy>";

        echo "</script>";


  echo "</scripts>";


?>
