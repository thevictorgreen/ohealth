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
  $pt_id  = $_GET['pt_id'];
  $d_id   = $_GET['d_id'];
  $d_name = $_GET['d_name'];

  echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
  echo "<messages>";



  $query = "INSERT INTO $dr_allergies ( pt_id,d_id,d_name ) VALUES ( $pt_id,$d_id,AES_ENCRYPT('$d_name','$tde_key') )";
  mysql_query($query);


  echo "<message>DRUG ALLERGY ADDED</message>";

  echo "</messages>";

?>
