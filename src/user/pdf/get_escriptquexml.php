<?php

  header('Content-type: application/xml');

  $conn=mysql_connect("localhost","root","g0th@m");
  mysql_select_db("OPENHEALTH",$conn);


  //GET APPROPRIATE TABLE(S)
  $result = mysql_query("SELECT dr_prescriptions_que,api_key from gl_droffice where id = 1");
  $row = mysql_fetch_array($result);
  $prescript = $row['dr_prescriptions_que'];
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
  //$pt_id = $_GET['pt_id'];

  $result = mysql_query("SELECT
                         id,
                         aes_decrypt(dea,'$tde_key'),
                         aes_decrypt(dname,'$tde_key'),
                         aes_decrypt(oname,'$tde_key'),
                         aes_decrypt(daddr,'$tde_key'),
                         aes_decrypt(csz,'$tde_key'),
                         aes_decrypt(dphone,'$tde_key'),
                         pt_id,
                         aes_decrypt(first_name,'$tde_key'),
                         aes_decrypt(last_name,'$tde_key'),
                         aes_decrypt(dob,'$tde_key'),
                         aes_decrypt(addr,'$tde_key'),
                         aes_decrypt(city,'$tde_key'),
                         aes_decrypt(state,'$tde_key'),
                         aes_decrypt(zip,'$tde_key'),
                         aes_decrypt(phone,'$tde_key'),
                         aes_decrypt(ins_mri,'$tde_key'),
                         aes_decrypt(ins_name,'$tde_key'),
                         aes_decrypt(med,'$tde_key'),
                         aes_decrypt(qty,'$tde_key'),
                         aes_decrypt(refills,'$tde_key'),
                         aes_decrypt(sub_perm,'$tde_key'),
                         aes_decrypt(sig,'$tde_key'),
                         pharm_id
                         FROM $prescript");


  echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
  echo "<scripts>";

  while($row = mysql_fetch_array($result)) {

        $pharm_id = $row[23];
        $result1 = mysql_query("SELECT * FROM gl_pharmacy1 where id = $pharm_id");
        $row1 = mysql_fetch_array($result1);

        echo "<script>";
        echo "<id>" . $row[0] . "</id>";
        echo "<dea>" . $row[1] . "</dea>";
        echo "<dname>" . $row[2] . "</dname>";
        echo "<oname>" . $row[3] . "</oname>";
        echo "<daddr>" . $row[4] . "</daddr>";
        echo "<csz>" . $row[5] . "</csz>";
        echo "<dphone>" . $row[6] . "</dphone>";
        echo "<pt_id>" . $row[7] . "</pt_id>";
        echo "<first_name>" . $row[8] . "</first_name>";
        echo "<last_name>" . $row[9] . "</last_name>";
        echo "<pt_name>" . $row[8] . " " . $row[9] . "</pt_name>";
        echo "<dob>" . date("M-j-Y",strtotime($row[10])) . "</dob>";
        echo "<addr>" . $row[11] . "</addr>";
        echo "<city>" . $row[12] . "</city>";
        echo "<state>" . $row[13] . "</state>";
        echo "<zip>" . $row[14] . "</zip>";
        echo "<phone>" . $row[15] . "</phone>";
        echo "<ins_mri>" . $row[16] . "</ins_mri>";
        echo "<ins_name>" . $row[17] . "</ins_name>";
        echo "<med>" . $row[18] . "</med>";
        echo "<qty>" . $row[19] . "</qty>";
        echo "<refills>" . $row[20] . "</refills>";
        echo "<sub_perm>" . $row[21] . "</sub_perm>";
        echo "<sig>" . $row[22] . "</sig>";
        echo "<pharm_id>" . $row[23] . "</pharm_id>";
        echo "<pharmacy>" . $row1['name'] . "</pharmacy>";
        echo "<gaddr>" . $row1['addr'] . " " . $row1['city'] . " " . $row1['state'] . " " . $row1['zip'] . "</gaddr>";
        echo "<pphone>" . $row1['phone'] . "</pphone>";
        echo "</script>";
  }

  echo "</scripts>";

?>
