<?php

  header('Content-type: application/xml');
  //require_once("/logiscript/cert/settings.php");

  //$con = mysql_connect("localhost","root","g0th@m");
  //mysql_select_db("ACBHS",$con);

  $conn=mysql_connect("localhost","root","g0th@m");
  mysql_select_db("OPENHEALTH",$conn);

  function distance($lat1, $lon1, $lat2, $lon2, $unit) {

    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
        return ($miles * 1.609344);
    } else if ($unit == "N") {
        return ($miles * 0.8684);
    } else {
        return $miles;
    }
 }
 //USAGE---------------------------------------------------------------------
 //echo distance(32.9697, -96.80322, 29.46786, -98.53506, "m") . " miles<br>";
 //--------------------------------------------------------------------------
  function orderBy($data, $field) {
    $code = "return strnatcmp(\$a['$field'], \$b['$field']);";
    usort($data, create_function('$a,$b', $code));
    return $data;
  }

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

  $pt_id = $_GET['pt_id'];

  $result = mysql_query("select AES_DECRYPT(lat,'$tde_key') as plat,AES_DECRYPT(lng,'$tde_key') as plng from $dr_patients where id = $pt_id");
  $row = mysql_fetch_array($result);
  $plat = $row['plat'];
  $plng = $row['plng'];

  $i = 0;
  $data;

  $result = mysql_query("SELECT * FROM gl_pharmacy1");
  $number = mysql_num_rows($result);
  while($row = mysql_fetch_array($result)) {

        $id    = $row['id'];
        $name  = $row['name'];
        $addr  = $row['addr'];
        $city  = $row['city'];
        $state = $row['state'];
        $index = $name. "  " . $addr . " " . $city . " " . $state;
        $zip   = $row['zip'];
        $fax   = $row['fax'];
        $phone = $row['phone'];
        $phlat = $row['lat'];
        $phlng = $row['lng'];
        $dist  = distance($plat,$plng,$phlat,$phlng, "m");

        $data[$i] = array("id" => $id, "name" => $name, "addr" => $addr, "city" => $city, "state" => $state, "zip" => $zip, "fax" => $fax, "phone" => $phone, "phlat" => $phlat, "phlng" => $phlng, "dist" => $dist, "index" => $index);
        $i++;
  }

  $data = orderBy($data,'dist');

  echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
  echo "<drugstores>";


  for ($i = 0;$i < $number;$i++) {

        echo "<drugstore>";
        echo "<id>"     . $data[$i]['id'] . "</id>";
        echo "<name>"   . $data[$i]['name'] . "</name>";
        echo "<addr>"   . $data[$i]['addr'] . "</addr>";
        echo "<city>"   . $data[$i]['city'] . "</city>";
        echo "<state>"  . $data[$i]['state'] . "</state>";
        echo "<fax>"    . $data[$i]['fax'] . "</fax>";
        echo "<phone>"  . $data[$i]['phone'] . "</phone>";
        echo "<lat>"    . $data[$i]['phlat'] . "</lat>";
        echo "<lng>"    . $data[$i]['phlng'] . "</lng>";
        echo "<dist>"   . $data[$i]['dist'] . "</dist>";
        echo "<g_addr>" . $data[$i]['addr'] . " " . $data[$i]['city'] . " " . $data[$i]['state'] . " " . $data[$i]['zip'] . "</g_addr>";
        echo "<index>"  . $data[$i]['name'] . " " . $data[$i]['addr'] . " " . $data[$i]['city'] . " " . $data[$i]['state'] . " " . $data[$i]['zip'] . "</index>";
        echo "</drugstore>";

  }


  echo "<count>$number</count>";

  echo "</drugstores>";


?>
