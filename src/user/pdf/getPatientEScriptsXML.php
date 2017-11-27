<?php

  // Pull in the NuSOAP code
  require_once("nuSOAP/lib/nusoap.php");
  $url = "http://api.ameebatech.com/encode/verifyAPI.php?WSDL";
  $client = new nusoap_client($url);
  $err = $client->getError();
  if ($err) {
      echo '<p><b>Error: ' . $err . '</b></p>';
  }

  // GET API KEY
  require_once("/logiscript/cert/settings.php");

  $result = mysql_query("SELECT api_key FROM gl_droffice");
  $row = mysql_fetch_array($result);
  $api_key = $row['api_key'];


  // GET SALT
  $args     = array('apiKey' => "all", 'api_key' => "$api_key");
  $salt     = $client->call('getKey', array($args));

  //POSTED VARIABLES
  $pt_id = $_POST['pt_id'];
  $prescript = $_POST['prescriptions'];

  //require_once("/logiscript/cert/settings.php");

  $result = mysql_query("SELECT
                         id,
                         aes_decrypt(dea,'$salt'),
                         aes_decrypt(dname,'$salt'),
                         aes_decrypt(oname,'$salt'),
                         aes_decrypt(daddr,'$salt'),
                         aes_decrypt(csz,'$salt'),
                         aes_decrypt(dphone,'$salt'),
                         pt_id,
                         aes_decrypt(first_name,'$salt'),
                         aes_decrypt(last_name,'$salt'),
                         dob,
                         aes_decrypt(addr,'$salt'),
                         aes_decrypt(city,'$salt'),
                         aes_decrypt(state,'$salt'),
                         aes_decrypt(zip,'$salt'),
                         aes_decrypt(phone,'$salt'),
                         aes_decrypt(ins_mri,'$salt'),
                         aes_decrypt(ins_name,'$salt'),
                         aes_decrypt(med,'$salt'),
                         aes_decrypt(qty,'$salt'),
                         aes_decrypt(refills,'$salt'),
                         aes_decrypt(sub_perm,'$salt'),
                         aes_decrypt(sig,'$salt'),
                         pharm_id,
                         s_date,
                         s_time
                         FROM $prescript WHERE pt_id = $pt_id");


  echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
  echo "<patients>";

  while($row = mysql_fetch_array($result)) {

        $pharm_id = $row[23];
        $result1 = mysql_query("SELECT * FROM gl_pharmacy where id = $pharm_id");
        $row1 = mysql_fetch_array($result1);

        echo "<patient>";
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
        echo "<s_date>" . date("M-j-Y",strtotime($row[24])) . "</s_date>";
        echo "<s_time>" . $row[25] . "</s_time>";
        echo "</patient>";
  }

  echo "</patients>";

?>
