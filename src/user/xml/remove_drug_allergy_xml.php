<?php

  header('Content-type: application/xml');

  $conn=mysql_connect("localhost","root","g0th@m");
  mysql_select_db("OPENHEALTH",$conn);


  //GET APPROPRIATE TABLE(S)
  $result = mysql_query("SELECT dr_allergies,api_key from gl_droffice where id = 1");
  $row = mysql_fetch_array($result);
  $dr_allergies = $row['dr_allergies'];
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
  $al_id = $_GET['al_id'];

  echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
  echo "<messages>";



  $query = "DELETE FROM $dr_allergies WHERE al_id = $al_id";
  mysql_query($query);


  echo "<message>PATIENT DRUG ALLERGY REMOVED</message>";

  echo "</messages>";

?>
