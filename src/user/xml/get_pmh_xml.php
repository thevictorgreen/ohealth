<?php

  header('Content-type: application/xml');

  $conn=mysql_connect("localhost","root","g0th@m");
  mysql_select_db("OPENHEALTH",$conn);


  //GET APPROPRIATE TABLE(S)
  $result = mysql_query("SELECT dr_pmh,api_key from gl_droffice where id = 1");
  $row = mysql_fetch_array($result);
  $pmh = $row['dr_pmh'];
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


  echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
  echo "<pmhs>";

  //$result = mysql_query("SELECT * FROM $pmh WHERE pt_id = $pt_id");
  $result = mysql_query("SELECT phm_id,AES_DECRYPT(p1,'$tde_key'),AES_DECRYPT(p2,'$tde_key'),AES_DECRYPT(p3,'$tde_key'),AES_DECRYPT(p4,'$tde_key'),AES_DECRYPT(p5,'$tde_key'),AES_DECRYPT(p6,'$tde_key'),AES_DECRYPT(p7,'$tde_key'),AES_DECRYPT(p8,'$tde_key') FROM $pmh WHERE pt_id = $pt_id");
  $row = mysql_fetch_array($result);

  //while ($row = mysql_fetch_array($result)) {

         echo "<pmh>";

         echo "<pmh_id>" . $row[0]     . "</pmh_id>";
         echo "<p1>"     . $row[1]     . "</p1>";
         echo "<p2>"     . $row[2]     . "</p2>";
         echo "<p3>"     . $row[3]     . "</p3>";
         echo "<p4>"     . $row[4]     . "</p4>";
         echo "<p5>"     . $row[5]     . "</p5>";
         echo "<p6>"     . $row[6]     . "</p6>";
         echo "<p7>"     . $row[7]     . "</p7>";
         echo "<p8>"     . $row[8]     . "</p8>";

         echo "</pmh>";
  //}

  echo "</pmhs>";
?>
