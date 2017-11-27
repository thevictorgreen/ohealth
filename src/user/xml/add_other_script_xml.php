<?php

  header('Content-type: application/xml');

  $conn=mysql_connect("localhost","root","g0th@m");
  mysql_select_db("OPENHEALTH",$conn);


  //GET APPROPRIATE TABLE(S)
  $result = mysql_query("SELECT dr_prescriptions_other,api_key from gl_droffice where id = 1");
  $row = mysql_fetch_array($result);
  $dr_prescriptions_other = $row['dr_prescriptions_other'];
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


  //PATIENT VARIABLES
  $pt_id = $_GET['pt_id'];
  $doc   = $_GET['doc'];
  $med   = $_GET['med'];
  $sig   = $_GET['sig'];

  echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
  echo "<messages>";



  $query = "INSERT INTO $dr_prescriptions_other ( pt_id,doc,med,sig,os_date ) VALUES ( $pt_id,AES_ENCRYPT('$doc','$tde_key'),AES_ENCRYPT('$med','$tde_key'),AES_ENCRYPT('$sig','$tde_key'),CURDATE() )";
  mysql_query($query);


  echo "<message>OTHER SCRIPT ADDED</message>";

  echo "</messages>";

?>
