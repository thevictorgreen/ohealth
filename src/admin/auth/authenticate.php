<?php

  $uname = $_GET["uname"];
  $upass = $_GET["upass"];

//  if ($uname != NULL && $upass != NULL){

      //include the class and create a connection
//      include ("adLDAP.php");
//       try {
//              $adldap = new adLDAP();
//       }
//       catch (adLDAPException $e) {
//              echo $e;
//              exit();
//       }
       //authenticate the user
//       if ($adldap -> authenticate($uname,$upass)){

//              echo "authenticated";
//       }
//       else {
//              echo "failed authentication";
//       }
//  }


  if ($uname == "vgreen" && $upass == "newuser") {

      echo "authenticated";
  }

  else {

      echo "failed authentication";
  }

?>
