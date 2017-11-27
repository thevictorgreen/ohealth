<?php

  $con = mysql_connect("localhost","root","g0th@m"); //,false,MYSQL_CLIENT_SSL);
  mysql_select_db("OPENHEALTH",$con);

  //GET APPROPRIATE TABLE(S)
  $result = mysql_query("SELECT dr_patients,api_key from gl_droffice where id = 1");
  $row = mysql_fetch_array($result);
  $dr_patients = $row['dr_patients'];
  $api_key = $row['api_key'];
  //echo $api_key;

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

  //PATIENT PARAMETERS
  $pt_id  = $_GET['pt_id'];
  $patlat = $_GET['patlat'];
  $patlng = $_GET['patlng'];

  //INSERT INTO DATABASE
  $query = "UPDATE $dr_patients set lat=AES_ENCRYPT('$patlat','$tde_key'), lng=AES_ENCRYPT('$patlng','$tde_key') WHERE id = $pt_id";
  $result = mysql_query($query);


  echo "success";

?>
