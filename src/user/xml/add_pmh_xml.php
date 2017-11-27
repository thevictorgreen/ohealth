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
  $p1    = $_GET['p1'];
  $p2    = $_GET['p2'];
  $p3    = $_GET['p3'];
  $p4    = $_GET['p4'];
  $p5    = $_GET['p5'];
  $p6    = $_GET['p6'];
  $p7    = $_GET['p7'];
  $p8    = $_GET['p8'];

  echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
  echo "<messages>";

   $result = mysql_query("SELECT phm_id FROM $pmh where pt_id = $pt_id");

   if (mysql_num_rows($result) == 0) {

       $query = "INSERT INTO $pmh (pt_id,p1,p2,p3,p4,p5,p6,p7,p8) VALUES ($pt_id,AES_ENCRYPT('$p1','$tde_key'),AES_ENCRYPT('$p2','$tde_key'),AES_ENCRYPT('$p3','$tde_key'),AES_ENCRYPT('$p4','$tde_key'),AES_ENCRYPT('$p5','$tde_key'),AES_ENCRYPT('$p6','$tde_key'),AES_ENCRYPT('$p7','$tde_key'),AES_ENCRYPT('$p8','$tde_key'))";
       mysql_query($query);
   }

   if (mysql_num_rows($result) ==  1) {

       $query = "UPDATE $pmh SET p1=AES_ENCRYPT('$p1','$tde_key'),p2=AES_ENCRYPT('$p2','$tde_key'),p3=AES_ENCRYPT('$p3','$tde_key'),p4=AES_ENCRYPT('$p4','$tde_key'),p5=AES_ENCRYPT('$p5','$tde_key'),p6=AES_ENCRYPT('$p6','$tde_key'),p7=AES_ENCRYPT('$p7','$tde_key'),p8=AES_ENCRYPT('$p8','$tde_key') WHERE pt_id = $pt_id)";
       mysql_query($query);

   }

  echo "<message>Updated Past Medical History</message>";

  echo "</messages>";

?>
