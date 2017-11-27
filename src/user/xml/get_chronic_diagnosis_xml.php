<?php

  header('Content-type: application/xml');

  $conn=mysql_connect("localhost","root","g0th@m");
  mysql_select_db("OPENHEALTH",$conn);


  //GET APPROPRIATE TABLE(S)
  $result = mysql_query("SELECT dr_users,dr_diagnosis,api_key from gl_droffice where id = 1");
  $row = mysql_fetch_array($result);
  $dr_users = $row['dr_users'];
  $dr_diagnosis = $row['dr_diagnosis'];
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
  echo "<conditions>";



  $query = "SELECT diag_id,AES_DECRYPT(code,'$tde_key') as code,AES_DECRYPT(descr,'$tde_key') as descr,diag_date,u_id FROM $dr_diagnosis WHERE pt_id = $pt_id and AES_DECRYPT(d_type,'$tde_key') = 'Chronic'";
  $result = mysql_query($query);

  while($row = mysql_fetch_array($result)) {

        $u_id = $row['u_id'];
        $result1 = mysql_query("SELECT first_name,last_name FROM $dr_users where id = $u_id");
        $row1 = mysql_fetch_array($result1);

        echo "<condition>";

        echo "<diag_id>"   . $row['diag_id'] . "</diag_id>";
        echo "<diag_date>" . date("M-j-Y",strtotime($row['diag_date'])) . "</diag_date>";
        echo "<u_id>"      . $row1['first_name'] . " " . $row1['last_name'] . "</u_id>";
        echo "<code>"      . $row['code'] . "</code>";
        echo "<descr>"     . $row['descr'] . "</descr>";

        echo "</condition>";

  }



  echo "</conditions>";

?>
