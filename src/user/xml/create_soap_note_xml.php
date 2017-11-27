<?php

  header('Content-type: application/xml');

  $conn=mysql_connect("localhost","root","g0th@m");
  mysql_select_db("OPENHEALTH",$conn);


  //GET APPROPRIATE TABLE(S)
  $result   = mysql_query("SELECT dr_users,dr_soap,api_key from gl_droffice where id = 1");
  $row      = mysql_fetch_array($result);
  $dr_soap  = $row['dr_soap'];
  $dr_users = $row['dr_users'];
  $api_key  = $row['api_key'];

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
  $u_id   = $_GET['u_id'];


  $query = "INSERT INTO $dr_soap ( pt_id,u_id,soap_date,soap_time ) VALUES ( $pt_id,$u_id,CURDATE(),NOW() )";
  mysql_query($query);


  echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
  echo "<notes>";


  $result = mysql_query(" SELECT soap_id,u_id,soap_date,soap_time FROM $dr_soap WHERE pt_id = $pt_id" );

  while ($row = mysql_fetch_array($result)) {

         $u_id    = $row['u_id'];
         $soap_id = $row['soap_id'];

         $result1 = mysql_query("SELECT first_name,last_name FROM $dr_users WHERE id = $u_id");
         $row1 = mysql_fetch_array($result1);

         $result2 = mysql_query("SELECT AES_DECRYPT(ht,'$tde_key'),AES_DECRYPT(wt,'$tde_key'),AES_DECRYPT(bmi,'$tde_key'),AES_DECRYPT(bpmm,'$tde_key'),AES_DECRYPT(bphg,'$tde_key'),AES_DECRYPT(temp,'$tde_key'),AES_DECRYPT(pulse,'$tde_key'),AES_DECRYPT(resp,'$tde_key'),AES_DECRYPT(cc,'$tde_key'),AES_DECRYPT(s,'$tde_key'),AES_DECRYPT(o,'$tde_key'),AES_DECRYPT(p,'$tde_key') FROM $dr_soap WHERE soap_id = $soap_id");
         $row2 = mysql_fetch_array($result2);

         echo "<note>";

         echo "<soap_id>"   . $row['soap_id']   . "</soap_id>";
         echo "<soap_date>" . date("M-j-Y",strtotime($row['soap_date'])) . "</soap_date>";
         echo "<soap_time>" . $row['soap_time'] . "</soap_time>";
         echo "<u_id>"      . $row1['first_name'] . " " . $row1['last_name'] . "</u_id>";
         echo "<ht>"        . $row2[0]      . "</ht>";
         echo "<wt>"        . $row2[1]      . "</wt>";
         echo "<bmi>"       . $row2[2]     . "</bmi>";
         echo "<bpmm>"      . $row2[3]    . "</bpmm>";
         echo "<bphg>"      . $row2[4]    . "</bphg>";
         echo "<temp>"      . $row2[5]    . "</temp>";
         echo "<pulse>"     . $row2[6]   . "</pulse>";
         echo "<resp>"      . $row2[7]    . "</resp>";
         echo "<cc>"        . $row2[8]      . "</cc>";
         echo "<s>"         . $row2[9]       . "</s>";
         echo "<o>"         . $row2[10]      . "</o>";
         echo "<p>"         . $row2[11]      . "</p>";

         echo "</note>";
  }


echo "</notes>";

?>
