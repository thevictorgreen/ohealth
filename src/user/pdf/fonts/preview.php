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
  $dea          = $_POST['dea'];
  $dname        = $_POST['dname'];
  $oname        = $_POST['oname'];
  $daddr        = $_POST['addr'];
  $csz          = $_POST['csz'];
  $dphone       = $_POST['phone'];
  $pt_table     = $_POST['pt_table'];
  $dr_users     = $_POST['dr_users'];
  $pt_id        = $_POST['pt_id'];
  $initialVisit = $_POST['initialVisit'];

  $query = "SELECT AES_DECRYPT(first_name,'$salt'),AES_DECRYPT(last_name,'$salt'),dob,AES_DECRYPT(addr,'$salt'),AES_DECRYPT(city,'$salt'),AES_DECRYPT(state,'$salt'),AES_DECRYPT(zip,'$salt'),AES_DECRYPT(phone,'$salt'),ins_id,AES_DECRYPT(ins_mri,'$salt'),AES_DECRYPT(ins_grp,'$salt'),AES_DECRYPT(insyst,'$salt'),AES_DECRYPT(ssec,'$salt') FROM $pt_table WHERE id = $pt_id";
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

  $result = mysql_query("SELECT * FROM gl_insurance where ins_id = $ins_id ");
  $row = mysql_fetch_array($result);
  $ins_name = $row['name'];

  $result = mysql_query("SELECT lic_num FROM $dr_users WHERE dea = '$dea'");
  $row = mysql_fetch_array($result);
  $lic_num = $row['lic_num'];

  include ('class.ezpdf.php');
  $pdf = & new Cezpdf();
  $pdf->selectFont('./fonts/Helvetica.afm');

  //DEA AND LICENSE NUMBER
  $pdf->ezText('<b>DEA#:' . $dea  . '</b>',12,array('justification'=>'left'));
  $pdf->ezSetY(810);
  $pdf->ezText('<b>LIC#:' . $lic_num . '</b>',12,array('justification'=>'right'));
  $pdf->ezText('');
//  $pdf->ezText('<b>ALAMEDA COUNTY BEHAVIORAL</b>',20,array('justification'=>'centre'));
//  $pdf->ezText('<b>HEALTH CARE SERVICES</b>',20,array('justification'=>'centre'));



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

//$y = $pdf->ezText('<b>Insyst#: ' . $insyst . '</b>',12,array('justification'=>'left'));
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

  $med = $_POST['med'];
  $qty = $_POST['qty'];
  $sig = $_POST['sig'];
  $refills = $_POST['refills'];
  $sub_perm = $_POST['sub_perm'];


  $pdf->addText(60,$y,12,'<b>' . $med . '</b>');
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

  if ($initialVisit == "true") {
      $pdf->addJpegFromFile('checkedBox.jpg',460,135,25,25);
  }
  else {
      $pdf->addJpegFromFile('unCheckedBox.jpg',460,135,25,25);
  }

  //Patient Initial Visit Box
  $pdf->ezText('');
  //$pdf->ezSetY($y);
  //$pdf->ezText($y);

  $pdf->ezText('Patient Initial Visit',10,array('justification'=>'right'));

  //FOOTER
  $pdf->ezText('');
  $pdf->ezText('');
  $pdf->ezText('Copyright Logiscript Solutions 2010',6,array('justification'=>'right'));
  $pdf->ezText('Logical Prescription Processing',6,array('justification'=>'right'));
  $pdf->ezText('www.logiscript.com',6,array('justification'=>'right'));


   //PAGE 2 NETOWORK PHARMACY INFORMATION
   //$pdf->ezText('');
   //$pdf->ezText('');
//   $pdf->ezText('');
//   $pdf->ezText('');
   //$pdf->ezText('');
//   $pdf->ezText('<b>NETWORK PHARMACY INFORMATION</b>',17,array('justification'=>'centre'));
//   $pdf->ezText('<b>FOR</b>',17,array('justification'=>'centre'));
//   $pdf->ezText('<b>ALAMEDA COUNTY BEHAVIORAL HEALTH CARE SERVICES (BHCS)</b>',17,array('justification'=>'centre'));

   //Space
//   $pdf->ezText('');
//   $pdf->ezText('');

//   $pdf->ezText('<b><u>ALAMEDA COUNTY BHCS is the Payer of Last Resort:</u></b>',15,array('justification'=>'left'));
//   $pdf->ezText('<b>      1. Majority (~75%) have other coverage</b>',12,array('justification'=>'left'));
//   $pdf->ezText('<b>            a) Explore Medical, Medicare D, AAH, etc.</b>',12,array('justification'=>'left'));
//   $pdf->ezText('');
//   $pdf->ezText('<b>      2. BHCS ultimately pays for the uninsured prescription if:</b>',12,array('justification'=>'left'));
//   $pdf->ezText('<b>            a) BHCS prescription</b>',12,array('justification'=>'left'));
//   $pdf->ezText('<b>            b) BHCS Psychiatrist (see BHCS Medication & Pharmacy User Guide)</b>',12,array('justification'=>'left'));
//   $pdf->ezText('<b>                    http://www.acbhcs.org/meddir/user_guide.htm</b>',12,array('justification'=>'left'));
//   $pdf->ezText('<b>            c) BHCS formulary (see BHCS Medication & Pharmacy User Guide)</b>',12,array('justification'=>'left'));
//   $pdf->ezText('<b>            d) BHCS Client</b>',12,array('justification'=>'left'));

   //Space
//   $pdf->ezText('');
//   $pdf->ezText('');

//   $pdf->ezText('<b><u>BILLING NOTES:</u></b>',15,array('justification'=>'left'));
//   $pdf->ezText('<b>      1. PBM Processor InformedRx (Help Desk: (800) 777-0074)</b>',12,array('justification'=>'left'));
//   $pdf->ezText('<b>      2. Process claims using Bin# 610011</b>',12,array('justification'=>'left'));
//   $pdf->ezText('<b>      3. Process claims using 11 digit Insyst #</b>',12,array('justification'=>'left'));
//   $pdf->ezText('<b>      4. Process claims using:</b>',12,array('justification'=>'left'));
//   $pdf->ezText('<b>            a) Group # 05100 (most often: uninsured clients)</b>',12,array('justification'=>'left'));
//   $pdf->ezText('<b>            b) Group # 05180</b>',12,array('justification'=>'left'));
//   $pdf->ezText('                    Used for patients that have Medicare Part D, and BHCS is being billed for the copay, or',12,array('justification'=>'left'));
//   $pdf->ezText('                    medications not covered due to formulary restrictions',12,array('justification'=>'left'));
//   $pdf->ezText('<b>            c) Group # 05136</b>',12,array('justification'=>'left'));
//   $pdf->ezText('                    Used when the 5136 form is approved and faxed to the pharmacy by InformedRx.',12,array('justification'=>'left'));
//   $pdf->ezText('<b>            d) Group # 05150</b>',12,array('justification'=>'left'));
//   $pdf->ezText('                    Used at MIA pharmacies with ACBHCS medication on site',12,array('justification'=>'left'));

   //Space
//   $pdf->ezText('');
//   $pdf->ezText('');

//   $pdf->ezText('<b><u>Activating Clients</u></b>',15,array('justification'=>'left'));
//   $pdf->ezText('<b>      1. To load a new client into the county pay system, fax the original prescription</b>',12,array('justification'=>'left'));
//   $pdf->ezText('<b>          (with checked box Patients Initial BHCS Visit) to (630) 536-1235</b>',12,array('justification'=>'left'));

   //Space
//   $pdf->ezText('');
//   $pdf->ezText('');

//   $pdf->ezText('<b><u>InformedRx Help Desk</u></b>',15,array('justification'=>'left'));
//   $pdf->ezText('<b>            1. (800) 777-0074</b>',12,array('justification'=>'left'));

   //Space
//   $pdf->ezText('');
//   $pdf->ezText('');

//   $pdf->ezText('<b><u>BHCS Contact</u></b>',15,array('justification'=>'left'));
//   $pdf->ezText('<b>            1. Marianne Tavares (510) 567-8106</b>',12,array('justification'=>'left'));





  //WRITE FILE TO FOLDER
  //$pdfcode = $pdf->ezOutput();
  //$fp=fopen('SAVED.pdf','wb');
  //fwrite($fp,$pdfcode);
  //fclose($fp);

  //DISPLAY IN BROWSER
  $pdf->ezStream();

?>
