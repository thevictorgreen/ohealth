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
  $id         = $_GET['id'];
  $last_name  = $_GET['last_name'];
  $first_name = $_GET['first_name'];
  $dob        = $_GET['dob'];
  $addr       = $_GET['addr'];
  $city       = $_GET['city'];
  $state      = $_GET['state'];
  $zip        = $_GET['zip'];
  $phone      = $_GET['phone'];
  $cell       = $_GET['cell'];
  $email      = $_GET['email'];
  $ssec       = $_GET['ssec'];

  //INSERT INTO DATABASE
  $query = "UPDATE $dr_patients SET last_name=AES_ENCRYPT('$last_name','$tde_key'), first_name=AES_ENCRYPT('$first_name','$tde_key'), dob=AES_ENCRYPT('$dob','$tde_key'), addr=AES_ENCRYPT('$addr','$tde_key'), city=AES_ENCRYPT('$city','$tde_key'), state=AES_ENCRYPT('$state','$tde_key'), zip=AES_ENCRYPT('$zip','$tde_key'),  phone=AES_ENCRYPT('$phone','$tde_key'), cell=AES_ENCRYPT('$cell','$tde_key'), email=AES_ENCRYPT('$email','$tde_key'), ssec=AES_ENCRYPT('$ssec','$tde_key') WHERE id = $id";
  mysql_query($query);
  echo "success";

?>
