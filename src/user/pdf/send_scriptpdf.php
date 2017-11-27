<?php

  header('Content-type: application/xml');


  $conn=mysql_connect("localhost","root","g0th@m");
  mysql_select_db("OPENHEALTH",$conn);


  //GET APPROPRIATE TABLE(S)
  $result = mysql_query("SELECT name,addr,city,state,zip,phone,dr_patients,dr_users,dr_prescriptions,api_key from gl_droffice where id = 1");
  $row = mysql_fetch_array($result);
  $dr_patients = $row['dr_patients'];
  $dr_users = $row['dr_users'];
  $prescript = $row['dr_prescriptions'];
  $api_key = $row['api_key'];

  //DR OFFICE VALUES. OFFICE NAME,ADDRESS,CITY,STATE,ZIP, PHONE NUMBER
  $oname  = $row['name'];
  $daddr  = $row['addr'];
  $dcity  = $row['city'];
  $dstate = $row['state'];
  $dzip   = $row['zip'];
  $csz    = $dcity . " " . $state . " " . $zip;
  $dphone = $row['phone'];

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

  $md_id      = $_GET['md_id'];
  $pt_id      = $_GET['pt_id'];
  $medication = $_GET['med'];
  $qty        = $_GET['qty'];
  $sig        = $_GET['sig'];
  $refills    = $_GET['refills'];
  $sub_perm   = $_GET['sub_perm'];
  $ph_id      = $_GET['ph_id'];
  $med_id     = $_GET['med_id'];


  //DR PARAMETERS. NAME,DEA,LIC
  $result  = mysql_query("select first_name,last_name,login,dea,lic_num from $dr_users where id = $md_id");
  $row     = mysql_fetch_array($result);
  $dname   = $row['first_name'] . " " . $row['last_name'];
  $dea     = $row['dea'];
  $lic_num = $row['lic_num'];
  $login   = $row['login'];



  //PRESCRIPTION PARTAMETERS
  $query = "SELECT AES_DECRYPT(first_name,'$tde_key'),AES_DECRYPT(last_name,'$tde_key'),AES_DECRYPT(dob,'$tde_key'),AES_DECRYPT(addr,'$tde_key'),AES_DECRYPT(city,'$tde_key'),AES_DECRYPT(state,'$tde_key'),AES_DECRYPT(zip,'$tde_key'),AES_DECRYPT(phone,'$tde_key'),ins_id,AES_DECRYPT(ins_mri,'$tde_key'),AES_DECRYPT(ins_grp,'$tde_key'),AES_DECRYPT(insyst,'$tde_key'),AES_DECRYPT(ssec,'$tde_key') FROM $dr_patients WHERE id = $pt_id";
  $result = mysql_query($query);
  $row = mysql_fetch_array($result);
  $first_name = $row[0];
  $last_name  = $row[1];
  $dob        = $row[2];
  $addr       = $row[3];
  $city       = $row[4];
  $state      = $row[5];
  $zip        = $row[6];
  $phone      = $row[7];
  $ins_id     = $row[8];
  $ins_mri    = $row[9];
  $ins_grp    = $row[10];
  $insyst     = $row[11];
  $ssec       = $row[12];

  //INSURANCE PARAMETERS
  $result = mysql_query("SELECT * FROM gl_insurance where ins_id = $ins_id ");
  $row = mysql_fetch_array($result);
  $ins_name = $row['name'];


  include('class.ezpdf.php');
  $pdf = & new Cezpdf();
  $pdf->selectFont('./fonts/Helvetica.afm');

  //DEA AND LICENSE NUMBER
  $pdf->ezText('<b>DEA#:' . $dea  . '</b>',12,array('justification'=>'left'));
  $pdf->ezSetY(810);
  $pdf->ezText('<b>LIC#:' . $lic_num . '</b>',12,array('justification'=>'right'));
  $pdf->ezText('');


  //SPACE
  //$pdf->ezText('');
  $pdf->ezText('');
  $pdf->ezText('');

  //DR'S NAME AND DR OFFICE INFORMATION
  $pdf->ezText('<b>' . $dname . ', M.D.</b>',18,array('justification'=>'centre'));
  $pdf->ezText('<b>' . $oname . '</b>',16,array('justification'=>'centre'));
  $pdf->ezText('<b>' . $daddr . '</b>',12,array('justification'=>'centre'));
  $pdf->ezText('<b>' . $csz . '</b>',12,array('justification'=>'centre'));

  $y = $pdf->ezText('<b>' . $dphone . '</b>',12,array('justification'=>'centre'));

  //DOUBLE LINES
  $y = $y - 20;
  $pdf->line(30,$y,560,$y);
  $y = $y - 5;
  $pdf->line(30,$y,560,$y);


  //PATIENT INFORMATION
  $y = $y -20;
  $pdf->ezSetY($y);

  $y = $pdf->ezText('<b>' . $first_name . " " . $last_name . '</b>',12,array('justification'=>'left'));
  $pdf->addText(450,$y,12,'<b>DOB: ' . $dob . '</b>');

  $y = $pdf->ezText( '</b>',12,array('justification'=>'left'));
  $pdf->addText(450,$y,12,'<b>SSN: ' . $ssec . '</b>');

  $y = $pdf->ezText('<b>' . $addr . '</b>',12);
  $pdf->addText(445,$y,12,'<b>DATE: ' . date("m/d/y")  . '</b>');

  $y = $pdf->ezText('<b>' . $city . "," . $state . " " . $zip . '</b>');
  $pdf->addText(435,$y,12,'<b>PHONE: ' . $phone .  '</b>');

  $pdf->ezText('');

  //PATIENT INSURANCE INFORMATION
  $pdf->ezText('<b>' . $ins_name  . '</b>');
  $pdf->ezText('<b>INS ID: ' . $ins_mri  .'</b>');
  $y = $pdf->ezText('<b>INS GRP: ' . $ins_grp .'</b>');
  $y = $y - 100;


  //PRESCRIPTION DRUG STRENGTH QTY REFILLS DAW SIG
  $pdf->addJpegFromFile('rx.jpg',30,$y,25,25);
  $y = $y - 15;

  $pdf->addText(60,$y,12,'<b>' . $medication . '</b>');
  //$y = $pdf->ezText('<b>SUBSTITUTION PERMITTED: YES</b>');
  $y = $y - 15;
  $pdf->addText(60,$y,12,'<b>QUANITY: ' . $qty . '     REFILLS: ' . $refills . '</b>');
  $y = $y - 15;
  $pdf->addText(60,$y,12,'<b>SUBSTITUTION PERMITTED: ' . $sub_perm . '</b>');

  $y = $y - 30;
  $pdf->addText(60,$y,12,'<b>' . $sig . '</b>');


  //DR SIGNATURE
  $y = $y - 150;
  $pdf->line(100,$y,500,$y);
  $y = $y - 10;
  $pdf->ezSetY($y);
  $pdf->ezText('(SIGNATURE)',12,array('justification'=>'centre'));

  $pdf->ezText('');

  //FOOTER
  $pdf->ezText('');
  $pdf->ezText('');
  $pdf->ezText('Copyright Logiscript Solutions 2010',6,array('justification'=>'right'));
  $pdf->ezText('Logical Prescription Processing',6,array('justification'=>'right'));
  $pdf->ezText('www.logiscript.com',6,array('justification'=>'right'));

  //WRITE FILE TO FOLDER
  $pdfcode = $pdf->ezOutput();
  $script = $login . ".pdf";
  $fp=fopen($script,'wb');
  fwrite($fp,$pdfcode);
  fclose($fp);


  //GET EFAX PARAMETERS
  $service_url = 'http://api.firstmedisource.com/call.php/sendefax?user_key=' . $api_key;
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

  $username = $decoded[0]["username"];
  $password = $decoded[0]["password"];

  $result = mysql_query("select fax from gl_pharmacy1 where id = $ph_id");
  $row = mysql_fetch_array($result);
  $faxnumber = $row['fax'];

  $filename = $script;
  $filetype = 'PDF';

  //OPEN FILE
  if ( !($fp = fopen($filename,"r"))) {
       //error opening file
       echo "error opening file";
       exit;
  }

  //Read data from the file into $data
  $data = "";
  while(!feof($fp)) $data .= fread($fp,1024);
  fclose($fp);

  //GET EFAX SOAPCLIENT
  $client = new SoapClient("http://api.firstmedisource.com/efax_service.xml");
  $params->Username  = $username;
  $params->Password  = $password;
  $params->FaxNumber = $faxnumber;
  $params->FileData  = $data;
  $params->FileType  = $filetype;

  //SEND EFAX TO PHARMACY
  $result = $client->Sendfax($params);

  //SEND XML RESPONSE TO CLIENT
  echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
  echo "<messages>";

  if ($result->SendfaxResult > 0) {
     // RECORD SENT PRESCRIPTION IN DATABASE
      $query = "INSERT INTO $prescript( dea,dname,oname,daddr,csz,dphone,first_name,last_name,dob,addr,city,state,zip,phone,ins_mri,ins_name,med,qty,refills,sub_perm,sig,pharm_id,pt_id,s_date,s_time,d_id,doc_id )VALUES( AES_ENCRYPT('$dea','$tde_key'),AES_ENCRYPT('$dname','$tde_key'),AES_ENCRYPT('$oname','$tde_key'),AES_ENCRYPT('$daddr','$tde_key'),AES_ENCRYPT('$csz','$tde_key'),AES_ENCRYPT('$dphone','$tde_key'),AES_ENCRYPT('$first_name','$tde_key'),AES_ENCRYPT('$last_name','$tde_key'),AES_ENCRYPT('$dob','$tde_key'),AES_ENCRYPT('$addr','$tde_key'),AES_ENCRYPT('$city','$tde_key'),AES_ENCRYPT('$state','$tde_key'),AES_ENCRYPT('$zip','$tde_key'),AES_ENCRYPT('$phone','$tde_key'),AES_ENCRYPT('$ins_mri','$tde_key'),AES_ENCRYPT('$ins_name','$tde_key'),AES_ENCRYPT('$medication','$tde_key'),AES_ENCRYPT('$qty','$tde_key'),AES_ENCRYPT('$refills','$tde_key'),AES_ENCRYPT('$sub_perm','$tde_key'),AES_ENCRYPT('$sig','$tde_key'),$ph_id,$pt_id,CURDATE(),NOW(),$med_id,$md_id)";
      mysql_query($query);

          echo "<message>success</message>";
  }

  else {
          echo "<message>failure</message>";
  }

  echo "</messages>";

?>
